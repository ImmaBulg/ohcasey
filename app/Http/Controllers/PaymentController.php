<?php

namespace App\Http\Controllers;

use App\Jobs\MarkPaidPayment;
use App\Models\CartSet;
use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Show\Product;
use App\Models\Shop\Offer;
use App\Models\Shop\Photo;
use App\Models\Payment;
use App\Ohcasey\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Страница содержащая форму на редирект на робокассу.
     *
     * @param Payment $payment
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doPay(Payment $payment, Request $request)
    {
        if ($payment->isPaid()) {
            return redirect()->to('/');
        }

        $payment->load('order.cart.cartSetCase');
        $products = array();
        foreach (CartSetProduct::where('cart_id', $payment->order->cart->cart_id)->get() as $cart_set_prodcut)
        {
            $offer_id = $cart_set_prodcut->offer_id;
            $product_id = Offer::where('id', $offer_id)->value('product_id');
            $product =  \App\Models\Shop\Product::whereId($product_id)->get();
            array_push($products, [$product[0], $cart_set_prodcut->item_count]);
        }

        return view('site.payment')->with([
            'payment'          => $payment,
            'wasErrorTryAgain' => $request->get('wasErrorTryAgain', false),
            'products' => $products,
        ]);
    }

    /**
     * Редирект на робокассу.
     *
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Idma\Robokassa\Exception\EmptyDescriptionException
     * @throws \Idma\Robokassa\Exception\InvalidInvoiceIdException
     * @throws \Idma\Robokassa\Exception\InvalidSumException
     */
    public function processPay(Payment $payment)
    {
        /** @var \Idma\Robokassa\Payment $robokassaPayment */
        $robokassaPayment = app('payment');

        $robokassaPayment
            ->setInvoiceId($payment->id)
            ->setSum($payment->amount)
            ->setDescription('Оплата заказа #' . $payment->order->order_id);

		// var_dump($robokassaPayment->getPaymentUrl());exit;
        return redirect()->to($robokassaPayment->getPaymentUrl());
    }

    /**
     * Хэндлер перехода от робокассы.
     *
     * @param Request $request
     * @return string
     */
    public function check(Request $request)
    {
        /** @var \Idma\Robokassa\Payment $robokassaPayment */
        $robokassaPayment = app('payment');

        if ($robokassaPayment->validateResult($request->all())) {
            /** @var Payment $payment */
            $payment = Payment::find($robokassaPayment->getInvoiceId());

            if ($robokassaPayment->getSum() == $payment->amount) {
                if (! $payment->isPaid()) {
                    dispatch(new MarkPaidPayment($payment));
                }

                return $robokassaPayment->getSuccessAnswer();
            }
        }
    }

    /**
     * Хэндлер перехода от робокассы.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        /** @var \Idma\Robokassa\Payment $robokassaPayment */
        $robokassaPayment = app('payment');

        if ($robokassaPayment->validateSuccess($request->all())) {
            try {
                /** @var Payment $payment */
                $payment = Payment::findOrFail($robokassaPayment->getInvoiceId());
                
                if ($payment->isPaid()) {

                    if ($this->cartHasPaymentOrder($payment)) {
                        session(['cartId' => null]);
                    }

                    return redirect()->route('payment.success_view', [
                        'paymentHash' => $payment->hash,
                    ]);
                }
            } catch (ModelNotFoundException $e) { }
        }

        return redirect()->route('payment.failure_view');
    }

    /**
     * Странциа просто оплаты.
     *
     * @param Payment $payment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function successView(Payment $payment)
    {
        $cart = $payment->order->cart->load('cartSetCase');
        $setCase = $cart->cartSetCase;
        $view = 'site.payments.success';

        $hasNewProduct = ($setCase->first(function ($index, CartSetCase $case) {
            return $case->offer_id;
        }, null) || ($payment->order->cart->cartSetProducts->count() > 0));

        if ($hasNewProduct) {
            $view = 'site.payments.success_shop_design';
        }

        return view($view, [
            'payment' => $payment,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failure(Request $request)
    {

        $payment = null;

        try {
            /** @var Payment $payment */
            $payment = Payment::find($request->get('InvId'));
            if ($payment) {
                if($this->cartHasPaymentOrder($payment))
                    return redirect()->route('cart2')->with('wasErrorTryAgain', true);

                return redirect()->route('payment.do_pay', [
                    'paymentHash'      => $payment->hash,
                    'wasErrorTryAgain' => true,
                ]);
            }
        } catch (\Exception $e) { }

        return redirect()
            ->route('payment.failure_view')
            ->with('wasErrorTryAgain', $payment);
    }

    /**
     * Страница просмотра failure результата.
     *
     * @return \Illuminate\View\View
     */
    public function failureView()
    {
        return view('site.payments.failure', [
            'payment' => session('failurePayment', null),
        ]);
    }

    public function cartHasPaymentOrder(Payment $payment)
    {
        $cart = \App::make(Cart::class);
        return $cart->get()->exists() && $payment->order_id == $cart->get()->cart_order_id;
    }
}