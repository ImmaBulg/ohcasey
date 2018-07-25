<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CheckPaymentsByFilter;
use App\Jobs\CreatePaymentForOrder;
use App\Jobs\SendPaymentEmail;
use App\Jobs\SendPaymentSms;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Admin
 */
class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $paymentsQuery */
        $paymentsQuery = Payment::query()->orderBy('created_at', 'DESC');

        $paid = $request->get('is_paid', null);

        if ($paid == 'y') {
            $paymentsQuery->where('is_paid', true);
        } else if ($paid == 'n') {
            $paymentsQuery->where('is_paid', false);
        }

        if ($from = $request->get('created_at_from', '')) {
            $from = Carbon::parse($from)->startOfDay();
            $paymentsQuery->where('created_at', '>=', $from);
        }

        if ($to = $request->get('created_at_to', '')) {
            $to = Carbon::parse($to)->endOfDay();
            $paymentsQuery->where('created_at', '<=', $to);
        }

        if ($order_id = $request->get('order_id', '')) {
            $paymentsQuery->where('order_id', $order_id);
        }

        if ($id = $request->get('id', '')) {
            $paymentsQuery->where('id', $id);
        }

        $doCheck = $request->get('check', 0);

        /** @var Collection|Payment[] $markedPayments */
        $markedPayments = collect([]);

        if ($doCheck) {
            $queryForCheck = clone $paymentsQuery;
            $queryForCheck->where('is_paid', false);

            if ($queryForCheck->getQuery()->getCountForPagination() > 1000) {
                return redirect()
                    ->route('admin.payment.payment_list')
                    ->withErrors(['Превышен лимит на проверку: 1000 оплат']);
            }

            $markedPayments = dispatch(new CheckPaymentsByFilter($paymentsQuery));
        }

        return view('admin.payment.index')->with([
            'payments'       => $paymentsQuery
                ->paginate()
                ->appends(\Request::except('page')),
            'wasCheck'       => $doCheck,
            'markedPayments' => $markedPayments,
        ]);
    }

    /**
     * @param Payment $payment
     * @return array
     */
    public function ajaxDelete(Payment $payment)
    {
        /** @var \Idma\Robokassa\Payment $robokassa */
        $robokassa = app('payment');

        $cannotDelete = [
            $robokassa::PAYMENT_GOOD_DONE,
            $robokassa::PAYMENT_MONEY_GIVEN,
            $robokassa::PAYMENT_PROCESSING,
        ];

        $paymentStatus = $robokassa->getPaymentStatus($payment->getKey());

        if (!$payment->isPaid() && !in_array($paymentStatus, $cannotDelete)) {
            $payment->delete();
            return [
                'result' => 'success'
            ];
        }

        return [
            'result' => 'error',
            'error' =>  'Не удалось удалить. Статус оплаты:' . $paymentStatus
        ];
    }

    /**
     * @param Order $order
     * @return View
     */
    public function order(Order $order)
    {
        return view('admin.payment.order')->with([
            'order' => $order
        ]);
    }

    /**
     * Отправить письмо клиенту.
     *
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailSend(Payment $payment)
    {
        try {
            dispatch(new SendPaymentEmail($payment));
        } catch (\Exception $e) {
            return redirect()->to(\URL::previous() . '#payment')->withErrors([$e->getMessage()]);
        }

        return redirect()->back()->with('success', ['Письмо отправлено']);
    }

    /**
     * Отправить СМС клиенту.
     *
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function smsSend(Payment $payment)
    {
        try {
            dispatch(new SendPaymentSms($payment));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->to(\URL::previous() . '#payment')->with('success', ['СМС отправлено']);
    }

    /**
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPayment(Order $order, Request $request)
    {
        dispatch(new CreatePaymentForOrder($order, $request->get('amount')));

        return redirect()->to(\URL::previous() . '#payment')->with('success', ['Оплата создана']);
    }
}