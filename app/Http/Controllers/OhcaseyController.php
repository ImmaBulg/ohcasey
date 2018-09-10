<?php

namespace App\Http\Controllers;

use App\Jobs\CreatePaymentForOrder;
use App\Models\CdekCity;
use App\Models\Country;
use App\Models\Device;
use App\Models\Item;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PromotionCode;
use App\Models\Share;
use App\Ohcasey\Cart;
use App\Ohcasey\Delivery\Cdek\Cdek;
use App\Ohcasey\Ohcasey as Oh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Imagick;

/**
 * Class ControlPanel
 * @package App\Http\Controllers
 */
class OhcaseyController extends Controller
{
    /**
     * Index page
     * @param Cart $cart
     * @param Oh $o
     * @param Request $request
     * @return mixed
     */
    public function index(Cart $cart, Oh $o, Request $request)
    {
        session([
            'isAdminEdit' => false
        ]);
        $source = session()->pull('source');
        $summary = $cart->exists() ? $cart->get()->summary : null;

        return view('site.main', [
            'cartCount' => $summary ? $summary->cnt : 0,
            'share' => $o->getShare(),
            'source' => $source
        ]);
    }

    /**
     * Got to share
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function goToShare(Request $request, $id)
    {
		// кастыль для кнопки "добавить текст" в карточке товара
		if((isset($_GET["p"])) && ($_GET["p"] == 1)){
			$data_array = array(
				"DEVICE" => array(
					"bg" => $_GET["bgName"],
					"mask" => "[]",
					"type" => "system",
					"casey" => $_GET["caseFileName"],
					"color" => $_GET["deviceColorIndex"],
					"device" => $_GET["deviceName"],
				)
			);		
			session(['source' => $data_array]);
		}else{
			$share = Share::find($id);
			if ($share) {
				$share->share_used++;
				$share->save();
				session(['source' => $share->share_source]);
			}
		}

        return redirect()->to($this->addUtmToUrl(url('custom')));
    }

    /**
     * Share constructor value
     * @param Oh $o
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Oh $o, Request $request)
    {
        $o->saveShare($request->input('current', null));
        return response('OK');
    }

    /**
     * Get info
     * @param Request $request
     * @return mixed
     */
    public function info(Request $request)
    {
        $current = $request->input('current');
        $mDevice = Device::find(array_get($current, 'DEVICE.device'));
        $mCase = $mDevice ? Item::find(Oh::SKU_DEVICE) : null;
        return response()->json([
            'priceName' => $mDevice ? $mDevice->device_caption : '',
            'priceValue' => $mCase ? $mCase->item_cost : '0'
        ]);
    }

    /**
     * Cart
     * @param Cart $cart
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart(Cart $cart)
    {
        $summary = $cart->exists() ? $cart->get()->summary : null;

        return view('site.cart',
            [
                'cartCount' => $summary ? $summary->cnt : 0,
                'priceValue' => $summary ? $summary->amount : 0
            ]);
    }

    /**
     * Cart
     * @param Cart $cart
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart2(Cart $cart)
    {
        $summary = $cart->exists() ? $cart->get()->summary : null;

        return view('site.cart2',
            [
                'cartCount' => $summary ? $summary->cnt : 0,
                'priceValue' => $summary ? $summary->amount : 0,
                'payment_methods' => PaymentMethod::all(),
                'order' => $cart->get()->order
            ]);
    }

    /**
     * Cart put
     * @param Request $request
     * @param Cart $cart
     * @param Oh $o
     * @param $sku
     * @param int $count
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cartPut(Request $request, Cart $cart, Oh $o, $sku, $count = 1)
    {
        $current = $request->input('current');

        $cart->put(
            $sku,
            $count,
            (in_array($sku, [Oh::SKU_DEVICE, Oh::SKU_PRODUCT]) ? $current : null),
            $request->get('offer_id', null)
        );

        // временная акция
        $products = 0;
        if (empty($cart->get()->promotion_code_id) && $cart->get()->summary->cnt >= 3) {
            foreach ($cart->get()->cartSetProducts->load('offer.product') as $cartSetProduct)
                if ($cartSetProduct->offer->product->option_group_id === 1 || $cartSetProduct->offer->product->option_group_id === 2)
                {
                    $products++;
                }
            foreach ($cart->get()->cartSetCase as $cartSetCase)
                $products += $cartSetCase->item_count;
            if ($products >= 3)
                $this->cartAddCode($cart, 'HAPPY3');
        }
    //временная акция с доставкой
        /*if (empty($cart->get()->promotion_code_id) && $cart->get()->summary->cnt >= 2) {
            $this->cartAddCode($cart, 'DELIVERY_FREE');
        }*/

        /*if (empty($cart->get()->promotion_code_id) && $cart->get()->cartSetCase->count() >= 1  && $cart->get()->cartSetProducts->count() >= 1) {
            if ($cart->get()->cartSetCase->count() == 1 && $cart->get()->cartSetCase->first()->item_count >= 2)
            {
                foreach ($cart->get()->cartSetProducts->load('offer.product') as $cartSetProduct)
                    if ($cartSetProduct->offer->product->option_group_id === 8)
                    {
                        $this->cartAddCode($cart, 'FREE_CUP');
                        break;
                    }
            }
            else if ($cart->get()->cartSetCase->count() >= 2)
                foreach ($cart->get()->cartSetProducts->load('offer.product') as $cartSetProduct)
                    if ($cartSetProduct->offer->product->option_group_id === 8)
                    {
                        $this->cartAddCode($cart, 'FREE_CUP');
                        break;
                    }

        }*/

        $o->clearShare();

        return response('OK');
    }

    /**
     * Cart delete
     * @param Cart $cart
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cartDelete(Cart $cart, $id)
    {
        $cart->remove($id);

        // Promotion
        $products = 0;
        foreach ($cart->get()->cartSetProducts->load('offer.product') as $cartSetProduct)
            if ($cartSetProduct->offer->product->option_group_id === 1 || $cartSetProduct->offer->product->option_group_id === 2)
            {
                $products++;
            }
        foreach ($cart->get()->cartSetCase as $cartSetCase)
            $products += $cartSetCase->item_count;
        if (!empty($cart->get()->promotion_code_id) && $products < 3) {
            $this->cartRemoveCode($cart);
        }

        return response('OK');
    }

    /**
     * Cart promo code
     * @param Cart $c
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartAddCode(Cart $c, $code)
    {
        // Find code
        $code = PromotionCode
            ::where([
                ['code_value', '=', $code],
                ['code_enabled', '=', true]
            ])
            ->whereRaw('(code_valid_from is null or code_valid_from <= current_date)')
            ->whereRaw('(code_valid_till is null or current_date <= code_valid_till)')->get()->first();

        // Set code to cart
        if ($code) {
            $c->get()->update(['promotion_code_id' => $code->code_id]);
        }

        return response()->json(['code' => $code]);
    }

    /**
     * Cart promo code
     * @param Cart $cart
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cartRemoveCode(Cart $cart)
    {
        $cart->get()->update(['promotion_code_id' => null]);
        return response();
    }

    /**
     * Get smile image
     * @param $type
     * @param $size
     * @param $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function image($type, $size, $name)
    {
        $name = basename($name);
        $in = storage_path('app/' . $type . '/' . $name);
        $size = intval($size);
        if (!file_exists($in) || $size < 10 || $size > 500) {
            return response('Not found', 404);
        }

        $out = storage_path('app/generated/' . $type . '/' . $size . '/' . $name);
        if (!file_exists($out) || !filesize($out)) {
            Storage::put('generated/' . $type . '/' . $size . '/' . $name, '');

            $imagick = new Imagick(realpath($in));
            $imagick->resizeImage($size, $size, \Imagick::FILTER_LANCZOS, 1, true);
            $imagick->writeImage($out);
        }

        return response()->file($out, ['Content-Type' => 'image/png']);
    }

    /**
     * Font to image
     * @param Oh $o
     * @param Request $request
     * @return string
     */
    public function fontToImage(Oh $o, Request $request)
    {
        $font = $request->input('font');
        $color = $request->input('color', '#000000');
        $text = $request->input('text', 'Sample');
        $size = floatval($request->input('size', 30));

        $imagick = $o->fontToImage($font, $text, $size, $color);
        return response($imagick->getImageBlob(), 200, ['Content-Type' => 'image/png']);
    }

    /**
     * Font to image
     * @param Oh $o
     * @param Request $request
     * @return string
     */
    public function compile(Oh $o, Request $request)
    {
        // Input params
        $width = $request->input('width', 247);
        $height = $request->input('size', 487);
        $cart = json_decode($request->input('current', null), true);

        if ($cart) {
            $compiled = $o->compile($cart, $width, $height);
            $compiled->setCompression(\Imagick::COMPRESSION_BZIP);
            return response($compiled->getImageBlob(), 200, ['Content-Type' => 'image/png']);
        } else {
            return view('site.compile');
        }
    }

    /**
     * Get cities
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cdekCities(Request $request)
    {
        $mask = $request->input('mask', '%');
        $rowsOnPage = $request->input('rows_on_page', 30);

        $cities = CdekCity::where('city_name_full', 'ilike', $mask)->paginate($rowsOnPage);
        return response()->json($cities);
    }

    /**
     * Countries
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries(Request $request)
    {
        $mask = $request->input('mask', '%');
        $rowsOnPage = (int)$request->input('rows_on_page', 30);

        $countries = Country::where('country_name_ru', 'ilike', $mask)->paginate($rowsOnPage);
        return response()->json($countries);
    }

    /**
     * Get PVZ
     * @param Request $request
     * @param $city
     * @param null $code
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function cdekPvz(Request $request, $city, $code = null)
    {
        $cdek = new Cdek();
        return response()->json($cdek->pvz($city, $code));
    }

    /**
     * Get cost
     * @param Request $request
     * @param $city
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function cdekCost(Request $request, $city)
    {
        $cdek = new Cdek();
        return response()->json($cdek->cost($city));
    }

    /**
     * New order
     * @param Oh $o
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function order(Oh $o, Request $request, Cart $cart)
    {
        $fbProducts = [];
        foreach($cart->get()->cartSetProducts as $item){
            if ($item->offer) {
                $fbProducts[] = $item->offer->product->id;
            }
        }

        foreach($cart->get()->cartSetCase as $item){
            if ($item->offer) {
                $fbProducts[] = $item->offer->product->id;
            }
        }

        if (!$cart->get()->cartSet->count()) {
            return redirect(url('cart'));
        }

        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $city = $request->input('city');
        $country = $request->input('country');
        $pvz = $request->input('pvz');
        $postCode = $request->input('post_code');
        $comment = $request->input('comment');
        $deliveryType = $request->input('delivery_type');

        $deliveryDate = $request->input('delivery_date_' . $deliveryType, null);


        $address = $request->input($deliveryType . '_address');

        $utm = [];
        foreach ($request->cookies->all() as $c => $v) {
            if (substr($c, 0, 4) == 'utm_') {
                $utm[] = $c . '=' . $v;
            }
        }

        $payment_methods_id = $request->input('payment_methods_id');

        $orderId = $o->order(
            $name,
            $phone,
            $email,
            $deliveryType,
            $country,
            $city ?: null,
            $pvz ?: null,
            $deliveryDate,
            $payment_methods_id,
            $postCode,
            $address,
            $comment,
            implode(', ', $utm)
        )->order_id;

        /** @var Order $order */
        $order = Order::find($orderId);

        try {
            $o->orderCompile($order);
        } catch (\Exception $e) {
        }

        try {
            $o->orderMail($order);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        if ($order->payment_method && $order->payment_method->is_online) {
            $payment = dispatch(new CreatePaymentForOrder($order, $order->getTotalSum()));
            $order->processed_online_payment = true;
            $order->save();
            return redirect()->route('cart.payment', ['paymentHash' => $payment->hash]);
        }

        if ($request->has('new_cart')) {
            return response()->redirectToRoute('shop.cart.order.success_created', ['order' => $order]);
        }

        return view('site.orderId', [
            'order' => $order,
            'fbProducts' => $fbProducts,
            'cartCount' => null,
            'priceName' => null,
            'priceValue' => null
        ]);
    }

    /**
     * New order2
     * @param Oh $o
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function order2(Oh $o, Request $request, Cart $cart)
    {
        if (!$cart->get()->cartSet()->count()) {
            return redirect(url('cart'));
        }

        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $city = $request->input('city');
        $country = $request->input('country');
        $pvz = $request->input('pvz');
        $postCode = $request->input('post_code');
        $comment = $request->input('comment');
        $deliveryType = $request->input('delivery_type');

        $deliveryDate = $request->input('delivery_date_' . $deliveryType, null);

        $address = $request->input($deliveryType . '_address');

        $payment_methods_id = $request->input('payment_methods_id');

        $utm = [];
        foreach ($request->cookies->all() as $c => $v) {
            if (substr($c, 0, 4) == 'utm_') {
                $utm[] = $c . '=' . $v;
            }
        }

        $order = $o->order(
            $name,
            $phone,
            $email,
            $deliveryType,
            $country,
            $city ?: null,
            $pvz ?: null,
            $deliveryDate,
            $payment_methods_id,
            $postCode,
            $address,
            $comment,
            implode(', ', $utm)
        );

        try {
            $o->orderCompile($order);
        } catch (\Exception $e) {
        }

        if ($order->payment_method && $order->payment_method->is_online) {
            $payment = dispatch(new CreatePaymentForOrder($order, $order->getTotalSum()));
            return redirect()->route('cart.payment', ['paymentHash' => $payment->hash]);
        }

        try {
            $o->orderMail($order);
        } catch (\Exception $e) {
        }

        return view('site.orderId', [
            'order' => $order->order_id,
            'cartCount' => null,
            'priceName' => null,
            'priceValue' => null
        ]);
    }

    /**
     * Upload file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $name = $file->getFilename() . ($ext ? '.' . $ext : '');
        $file->move(storage_path('app/upload'), $name);
        return response()->json(['file' => basename($name)]);
    }

    /**
     * Delivery page
     */
    public function delivery()
    {
        return view('site.delivery');
    }

    /**
     * About
     */
    public function about()
    {
        return view('site.about');
    }

    /**
     * From
     * @param Oh $o
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function go(Oh $o, Request $request)
    {
        // Set current group
        $group = $request->input('g', null);
        if ($group) {
            $o->setCurrentGroup($group);
        }

        // Return redirect view
        return view('site.redirect', [
            'url' => $this->addUtmToUrl(url($request->input('url', '/')))
        ]);
    }

    /**
     * From
     * @param Oh $o
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function instaprofile(Oh $o, Request $request)
    {
        // Set current group
        $group = $request->input('g', null);
        if ($group) {
            $o->setCurrentGroup($group);
        }

        // Return redirect view
        return view('site.redirect', [
            'url' => $this->addUtmToUrl(url($request->input('url', '/')))
        ]);
    }

    protected function addUtmToUrl($url)
    {
        $query = '';
        onlyFilledUtmParameters()->each(function ($utm) use (&$query) {
            if ($utm->key == 'utm_source' && $utm->value == 'direct') {
                return;
            }
            $query .= urlencode($utm->key) . '=' . urlencode($utm->value) . '&';
        });
        if ($query) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $query;
        }
        return $url;
    }
}
