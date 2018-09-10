<?php

namespace App\Http\Controllers;

use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Ohcasey\Cart as CartHelper;
use App\Ohcasey\Promotion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use yii\helpers\VarDumper;

/**
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * @param CartHelper $cartHelper
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|array
     */
    public function index(CartHelper $cartHelper, Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->wantsJson()) {
            return ['data' => $this->cartObject($cartHelper)];
        }
        //dump($this->cartObject($cartHelper));
        return view('site.shop.cart.cart', $this->cartObject($cartHelper));
    }

    /**
     * @param CartHelper $cartHelper
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function process(CartHelper $cartHelper, Request $request)
    {
        /** @var Delivery[]|Collection $deliveryTypes */
        $deliveryTypes = Delivery::with('payment_methods')->get()->keyBy('delivery_name');
        $deliveryPayments = [];
        foreach ($deliveryTypes as $name => $delivery) {
            $deliveryPayments[$name] = $delivery->payment_methods->pluck('id');
        }
        /** @var PaymentMethod[]|Collection $payments */
        $payments = PaymentMethod::all()->keyBy('name');
        return view('site.shop.cart.process', [
                'deliveryTypes' => Delivery::all()->keyBy('delivery_name'),
                'payments' => $payments,
                'deliveryPayments' => $deliveryPayments,
                // 'isShort' => false,//(($request->has('short') || env('CART_SHOP_SHORT_FORM')) && !$request->has('full')),
				
                'isShort' => (($request->has('short') || env('CART_SHOP_SHORT_FORM')) && !$request->has('full')),
                // 'isShort' => true,
            ] + $this->cartObject($cartHelper));
    }

    /**
     * @param Request $request
     * @param CartHelper $cartHelper
     * @return array
     */
    public function updateDeliveryInfo(Request $request, CartHelper $cartHelper)
    {
        if ($cartHelper->exists()) {
            $cart = $cartHelper->get();
            $cart->delivery_amount = $request->get('delivery_amount');
            $cart->delivery_name = $request->get('delivery_name');
            $cart->save();
            return ['result' => 'success', 'data' => $this->cartObject($cartHelper)];
        }
        return ['result' => 'error'];
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(Order $order)
    {
        $GoogleDataLayer = (object)[
            'transactionId' => $order->order_id,
            'transactionTotal' => $order->order_amount,
            'transactionShipping' => $order->delivery_amount,
            'transactionProducts' => [],
        ];

        foreach ($order->cart->cartSetCase as $item) {
            $GoogleDataLayer->transactionProducts[] = (object)[
                'sku' => 'case',
                'name' => $item->device->device_caption . ', ' . $item->casey->case_caption,
                'price' => $item->item_cost,
                'quantity' => $item->item_count
            ];
        }

        foreach ($order->cart->cartSetProducts as $item) {
            $GoogleDataLayer->transactionProducts[] = (object)[
                'sku' => $item->offer->product->id,
                'name' => $item->offer->product->name,
                'price' => $item->item_cost,
                'quantity' => $item->item_count
            ];
        }

        $fbProducts = [];
        foreach ($order->cart->cartSetProducts as $item) {
            if ($item->offer) {
                $fbProducts[] = $item->offer->product->id;
            }
        }

        foreach ($order->cart->cartSetCase as $item) {
            if ($item->offer) {
                $fbProducts[] = $item->offer->product->id;
            }
        }

        return view('site.shop.cart.order_success', [
            'order' => $order,
            'fbProducts' => $fbProducts,
            //'GoogleDataLayer' => $GoogleDataLayer,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function emptyCart()
    {
        return view('site.shop.cart.empty');
    }

    /**
     * Объект корзины в едином формате для всех видов общения (передача во view/json в аяксе).
     *
     * @param CartHelper $cartHelper
     * @return array
     */
    protected function cartObject(CartHelper $cartHelper)
    {
        $itemsCount = $amount = 0;
        $userCart = [];
        $cartSetCases = collect([]);
        $cartSetProducts = collect([]);
        $priceWithDiscount = 0;

        if ($cartHelper->exists()) {
            $userCart = $cartHelper->get();
            $summary = $userCart->summary;
            $amount = $summary->amount;
            $itemsCount = $summary->cnt;
            $cartSetCases = $userCart->cartSetCase;
            $cartSetProducts = $userCart->cartSetProducts->load('offer.product');
            $priceWithDiscount = $amount;

            if ($userCart->promotionCode && $userCart->promotionCode->isActive()) {
                $promotion = new Promotion($userCart->promotionCode->getKey());
                $priceWithDiscount = ($amount - $promotion->getDiscount($userCart, $userCart->delivery_amount));
            }
        }
		
		$lastCategoryLink = null;
        $lastCase = null;
        $lastProduct = null;
		if(!empty($cartSetCases[0]) && !empty($cartSetCases[0]->offer) && !empty($cartSetCases[0]->offer->product) && !empty($cartSetCases[0]->offer->product->firstCategory())){
		    $lastCase = $cartSetCases[0]->offer->product;
		    $lastCategoryLink = route('shop.slug', $cartSetCases[0]->offer->product->firstCategory()->getUrlAttribute());
		}
        if(!empty($userCart->cartSetProducts[0]) && !empty($userCart->cartSetProducts[0]->offer) && !empty($userCart->cartSetProducts[0]->offer->product) && !empty($userCart->cartSetProducts[0]->offer->product->firstCategory())){
            $lastProduct = $userCart->cartSetProducts[0]->offer->product;
		    $lastCategoryLink = route('shop.slug', $userCart->cartSetProducts[0]->offer->product->firstCategory()->getUrlAttribute());
        }
        if (!empty($lastProduct) && !empty($lastCase))
            if ($lastProduct->updated_at > $lastCase->updated_at)
                $lastCategoryLink = $lastProduct->firstCategory()->getUrlAttribute();
		    else
		        $lastCategoryLink = $lastCase->firstCategory()->getUrlAttribute();
        return [
            'cartCount' => $itemsCount,
            'priceValue' => $amount,
            'priceWithDiscount' => $priceWithDiscount,
            'cart' => $userCart,
            'cartSetCases' => $cartSetCases,
            'cartSetProducts' => $cartSetProducts,
			'lastCategoryLink' => $lastCategoryLink,
        ];
    }

    /**
     * Обновить информацию о скидке
     *
     * @param CartHelper $cart
     */

    public function updateDiscountInfo($cartHellper)
    {
        $cart = $cartHellper->get();
        $products = 0;
        foreach ($cart->cartSetProducts->load('offer.product') as $cartSetProduct)
            if ($cartSetProduct->offer->product->option_group_id === 1 || $cartSetProduct->offer->product->option_group_id === 2)
            {
                $products++;
            }
        foreach ($cart->cartSetCase as $cartSetCase)
            $products += $cartSetCase->item_count;
        if ($products < 3)
        {
            $cart->promotion_code_id = null;
            if (isset($cart->order))  {
                $cart->order->discount_amount = 0;
                $cart->order->save();
            }
            $cart->save();
        }
        return $cart;
    }

    public function updateDiscountInfo2($cartHelper)
    {
        $cart = $cartHelper['cart'];
        $products = 0;
        foreach ($cart->cartSetProducts->load('offer.product') as $cartSetProduct)
            if ($cartSetProduct->offer->product->option_group_id === 1 || $cartSetProduct->offer->product->option_group_id === 2)
            {
                $products++;
            }
        foreach ($cart->cartSetCase as $cartSetCase)
            $products += $cartSetCase->item_count;
        if ($products < 3)
        {
            $cart->promotion_code_id = null;
            if (isset($cart->order))  {
                $cart->order->discount_amount = 0;
                $cart->order->save();
            }
            $cart->save();
        }
        return $cart;
    }

    /**
     * Обновить количество для товара корзины.
     *
     * @param CartSetCase $case
     * @param CartHelper $cartHelper
     * @param Request $request
     * @return array
     */
    public function updateCount(CartSetCase $case, CartHelper $cartHelper, Request $request)
    {
        if ($cartHelper->exists()) {
            /** @var CartSetCase $case */
            $case = $cartHelper->get()->cartSetCase->find($case->getKey(), null);
            if ($case) {
                $case->item_count = $request->get('count');
                $case->save();
                if ($cartHelper->get()->order) {
                    $order = $cartHelper->get()->order;
                    $order->order_amount = $order->getProductSum();
                    $order->save();
                }
                $this->updateDiscountInfo($cartHelper);
                return ['result' => 'success', 'data' => $this->cartObject($cartHelper)];
            } else {
                return ['result' => 'error'];
            }
        }

        return ['result' => 'error'];
    }

    /**
     * Обновить количество для товара корзины.
     *
     * @param CartSetProduct $product
     * @param CartHelper $cartHelper
     * @param Request $request
     * @return array
     */
    public function updateCountProduct(CartSetProduct $product, CartHelper $cartHelper, Request $request)
    {
        if ($cartHelper->exists()) {
            /** @var CartSetProduct $product */
            $product = $cartHelper->get()->cartSetProducts->find($product->getKey(), null);
            if ($product) {
                $product->item_count = $request->get('count');
                $product->cartSet->item_count = $request->get('count');
                $product->cartSet->save();
                $product->save();
                if ($cartHelper->get()->order) {
                    $order = $cartHelper->get()->order;
                    $order->order_amount = $order->getProductSum();
                    $order->save();
                }
                $this->updateDiscountInfo($cartHelper);
                return ['result' => 'success', 'data' => $this->cartObject($cartHelper)];
            } else {
                return ['result' => 'error'];
            }
        }

        return ['result' => 'error'];
    }

    /**
     * Удалить чехол из корзины.
     *
     * @param CartSetCase $case
     * @param CartHelper $cartHelper
     * @return array
     */
    public function removeCase(CartSetCase $case, CartHelper $cartHelper)
    {
        if ($cartHelper->exists()) {
            /** @var CartSetCase $case */
            $case = $cartHelper->get()->cartSetCase->find($case->getKey(), null);
            if ($case) {
                $case->delete();
                if ($cartHelper->get()->order) {
                    $order = $cartHelper->get()->order;
                    $order->order_amount = $order->getProductSum();
                    $order->save();
                }
                $counts = $this->updateDiscountInfo2($this->cartObject($cartHelper));
                return ['result' => 'success', 'data' => $this->cartObject($cartHelper), 'counts' => $counts];
            } else {
                return ['result' => 'error'];
            }
        }

        return ['result' => 'error'];
    }

    /**
     * Удалить товар из корзины.
     *
     * @param CartSetProduct $product
     * @param CartHelper $cartHelper
     * @return array
     */
    public function removeProduct(CartSetProduct $product, CartHelper $cartHelper)
    {
        if ($cartHelper->exists()) {
            /** @var CartSetCase $product */
            $product = $cartHelper->get()->cartSetProducts->find($product->getKey(), null);
            if ($product) {
                $cartSet = $product->cartSet;
                $product->delete();
                $cartSet->delete();
                if ($cartHelper->get()->order) {
                    $order = $cartHelper->get()->order;
                    $order->order_amount = $order->getProductSum();
                    $order->save();
                }
                $this->updateDiscountInfo2($this->cartObject($cartHelper));
                return ['result' => 'success', 'data' => $this->cartObject($cartHelper)];
            } else {
                return ['result' => 'error'];
            }
        }
        return ['result' => 'error'];
    }
}
