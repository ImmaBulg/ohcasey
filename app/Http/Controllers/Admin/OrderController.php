<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\OrderImplode;
use App\Models\Background;
use App\Models\CartSet;
use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Casey;
use App\Models\DeliveryCdek;
use App\Models\Device;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderStatus;
use App\Models\Shop\Offer;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Product;
use App\Ohcasey\Cart;
use App\Ohcasey\Ohcasey;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Sinergi\BrowserDetector as Br;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @param Order $order
     * @param $hash
     * @param $imgPath
     * @return mixed
     */
    public function image(Order $order, $hash, $imgPath)
    {
        return app()->call(app(\App\Http\Controllers\OrderController::class), func_get_args());
    }

    /**
     * Просмотр HTML страницы подходящей для печати.
     *
     * @param Request $request
     * @param Order $order
     * @param $hash
     * @throws ModelNotFoundException
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderPrint(Request $request, $order, $hash)
    {
        if ($order->order_hash != $hash) {
            throw (new ModelNotFoundException())->setModel($order);
        }

        $type = $request->input('type', 'admin');

        $os      = new Br\Os(data_get($order, 'cart.cart_user_agent', null));
        $browser = new Br\Browser(data_get($order, 'cart.cart_user_agent', null));

        $vars = [
            'order'   => $order,
            'os'      => $os->getName(),
            'browser' => $browser->getName(),
            'version' => $browser->getVersion(),
        ];

        switch ($type) {
            case 'client':
                $template = 'mail.client';
                break;
            case 'adminPrint':
                $vars['full'] = false;
                $template     = 'mail.order';
                break;
            case 'admin':
                $vars['full']      = true;
                $vars['printLink'] = route('admin.order.print',[
                    'order' => $order,
                    'hash'  => $order->order_hash,
                    'type'  => 'adminPrint',
                 ]);
                $template = 'mail.order';
                break;
            default:
                return response('Not found', 404);
        }

        return view($template, $vars);
    }

    /**
     * @param Order $order
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Order $order)
    {
        $sizes = OptionValue::where(['option_id' => 8])->orderBy('order', 'asc')->get();
        $prints = OptionValue::where(['option_id' => 11])->get();
        $print_statuses = OptionValue::where(['option_id' => 12])->get();
        $order->load([
            'status',
            'delivery',
            'country',
            'city',
            'cart.cartSet',
            'cart.cartSetCase',
            'cart.promotionCode',
            'deliveryRussianPost',
            'orderLogs.user',
            'specialItems',
        ]);

        $orders = Order::with(['status', 'cart', 'cart.cartSetCase'])
            ->where('order_id', '!=', $order->order_id)
            ->orderBy('order_id', 'desc')
            ->paginate(100);

        return view('admin.order.form')->with([
            'order'    => $order,
            'orders'   => $orders,
            'devices'  => Device::orderBy('device_name')->get(),
            'casey'    => Casey::orderBy('case_name')->get(),
            'statuses' => OrderStatus::orderBy('sort', 'DESC')->get(),
            'sizes'    => $sizes,
            'prints'   => $prints,
            'print_statuses' => $print_statuses,
        ]);
    }

    /**
     * Удаление позиции из заказа.
     *
     * @param Order $order
     * @param CartSetCase $cartSetCase
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function removeCartSetCase(Order $order, CartSetCase $cartSetCase)
    {
        if ($order->cart->cart_id == $cartSetCase->cart->cart_id) {
            \DB::beginTransaction();
            try {
                $order->order_amount -= ($cartSetCase->item_count * $cartSetCase->item_cost);
                $cartSetCase->delete();
                $order->save();

                OrderLog::create([
                    'order_id'    => $order->order_id,
                    'description' => 'Удалил товар',
                    'short_code'  => OrderLog::CUSTOM_CODE,
                ]);

                \DB::commit();
                return redirect()->to(\URL::previous() . '#cartSetCase')->with('success', ['Товар успешно удалён']);
            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        }

        return redirect()->back()->withErrors(['Ошибка удаления товара из заказа']);
    }

    /**
     * Удаление позиции из заказа.
     *
     * @param Order $order
     * @param CartSetProduct $cartSetProduct
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function removeCartSetProduct(Order $order, CartSetProduct $cartSetProduct)
    {
        if ($order->cart->cart_id == $cartSetProduct->cart->cart_id) {
            \DB::beginTransaction();
            try {
                $order->order_amount -= ($cartSetProduct->item_count * $cartSetProduct->item_cost);
                $cartSetProduct->delete();
                $cartSetProduct->cartSet->delete();
                $order->save();

                OrderLog::create([
                    'order_id'    => $order->order_id,
                    'description' => 'Удалил товар ' . $cartSetProduct->offer->product->name,
                    'short_code'  => OrderLog::CUSTOM_CODE,
                ]);

                \DB::commit();
                return redirect()->to(\URL::previous() . '#cartSetCase')->with('success', ['Товар успешно удалён']);
            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        }

        return redirect()->back()->withErrors(['Ошибка удаления товара из заказа']);
    }

    /**
     * Отменить объединение
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSwallow()
    {
        $destinationOrder = session('destination_order_implode', null);

        session(['destination_order_implode' => null]);

        if ($destinationOrder) {
            return redirect()->route('admin.order.show', $destinationOrder);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Оюъединить
     *
     * @param Order $order
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function swallow(Order $order)
    {
        /** @var Order $destinationOrder */
        $destinationOrder = session('destination_order_implode', null);

        if (! $destinationOrder) {
            return redirect()->back()->withErrors([
                'order_id' => 'Не найден заказ получатель'
            ]);
        }

        dispatch(new OrderImplode($destinationOrder, collect([$order])));

        session(['destination_order_implode' => null]);

        return redirect()->route('admin.order.show', $destinationOrder)->with('success', [
            'Объединение прошло успешно'
        ]);
    }

    /**
     * @param Order $order
     * @param Request $request
     * @param Ohcasey $helper
     * @throws
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Order $order, Request $request, Ohcasey $helper)
    {
        if ($request->get('implode', null)) {
            session([
                'destination_order_implode' => $order
            ]);
            return redirect()->route('admin.order.list')->with('success', [
                'Присоединение заказа к заказу #' . $order->order_id
            ]);
        }

        $msg = [];
        $errors = [];

        \DB::beginTransaction();
        try {
            if ($order->fill($request->get('order', []))) {
                if ($order->isDirty() && $order->save()) {
                    $msg[] = 'Информация о заказе обновлена!';
                }
            }

            if ($request->get('change_delivery', false)) {
                if ($order->deliveryRussianPost) {
                    $order->deliveryRussianPost->delete();
                }

                if ($order->deliveryCdek) {
                    $order->deliveryCdek->delete();
                }

                $values = [
                    'delivery_name'    => null,
                    'delivery_date'    => null,
                    'delivery_address' => null,
                    'delivery_amount'  => null,
                    'city_id'          => null,
                    'country_iso'      => null,
                ];

                $order->fill($values);

                $values['city_id']          = $request->input('city') ?: null;
                $values['country_iso']      = $request->input('country');
                $values['delivery_name']    = $request->input('delivery_type');
                $values['delivery_date']    = $request->input('delivery_date_' . $values['delivery_name'], null);
                $values['delivery_address'] = $request->input($values['delivery_name'] . '_address');

                $pvz      = $request->input('pvz');
                $postCode = $request->input('post_code');

                /** @var DeliveryCdek|null $delivery */
                list($deliveryCost, $delivery) = $helper->getSuitableDelivery(
                    $values['delivery_name'],
                    $values['country_iso'],
                    $values['city_id'],
                    $pvz,
                    $postCode
                );

                if ($delivery) {
                    $delivery->order()->associate($order)->save();
                }

                $values['delivery_amount'] = $deliveryCost;

                $order->fill($values)->save();

                $msg[] = 'Информация о доставке обновлена!';
            }

            if(! count($errors)) {
                \DB::commit();
            } else {
                \DB::rollBack();
                return redirect()->back()->withErrors($errors);
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Обновить статус заказа.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function ajaxStatusUpdate(Request $request, Order $order)
    {
        /** @var Order $order */
        if ($request->has('status')) {
            $order->order_status_id = (int) $request->get('status');
        }
		
		//изменение статуса оплаты
		if(in_array($order->order_status_id, [OrderStatus::STATUS_ID_FINISHED, OrderStatus::STATUS_ID_IN_PRINT])){
			$order->payments()->update(['is_paid' => true]);
			$order->processed_online_payment = false;
		}

        $order->save();

        return response()->json($order);
    }

    /**
     * Пересобрать картинки заказа.
     *
     * @param Ohcasey $ohcasey
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recompile(Ohcasey $ohcasey, Order $order)
    {
        $ohcasey->orderCompile($order, $ohcasey::CASE_WIDTH_DEFAULT, $ohcasey::CASE_HEIGHT_DEFAULT);

        return redirect()->to(\URL::previous() . '#cartSetCase');
    }

    /**
     * Resend email.
     *
     * @param Ohcasey $ohcasey
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderMail(Ohcasey $ohcasey, Order $order)
    {
        $ohcasey->orderMail($order);

        return back();
    }

    /**
     * Last order ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderLast()
    {
		// $last_id = (int)$_GET["last_id"];
		// $count_new = Order::where('order_id', '>', $last_id)->get()->count();
        // return response()->json(['last' => $count_new]);
		
        // return response()->json(['last' => Order::max('order_id')]);
		return response()->json(['last' => Order::where('order_ts', '>', '2017-12-22')->max('order_id')]);
    }

    /**
     * Delete order
     *
     * @param Order $order
     * @return void
     */
    public function delete(Order $order)
    {
        $order->delete();
    }

    /**
     * @param Order $order
     * @param CartSetCase $case
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cartSetCaseEdit(Order $order, CartSetCase $case)
    {
        session([
            'isAdminEdit' => true,
            'editOrder'   => $order,
            'editCase'    => $case
        ]);

        return view('site.main', [
            'cartCount'          => 0,
            'source'             => $case->item_source,
            'constructorPageUrl' => route('admin.order.cart_set_case.edit', ['order' => $order, 'cartSetCase' => $case]),
        ]);
    }

    /**
     * @param Order $order
     * @param CartSetCase $case
     * @param Ohcasey $ohcasey
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cartSetCaseStore(Order $order, CartSetCase $case, Ohcasey $ohcasey, Request $request)
    {
        $newSource = $request->get('current');
        \DB::transaction(function () use ($order, $newSource, $case, $ohcasey) {
            /** @var Casey $casey */
            $casey = Casey::findOrFail($newSource['DEVICE']['casey']);

            /** @var Device $device */
            $device = Device::findOrFail($newSource['DEVICE']['device']);

            $newSource['DEVICE']['deviceName'] = $device->device_caption;
            $newSource['DEVICE']['caseName'] = $casey->case_caption;

            $case->case_name = $newSource['DEVICE']['casey'];
            $case->device_name = $device->device_name;
            $case->item_source = $newSource;
            $case->save();
            $order->load('cart.cartSetCase');
            $ohcasey->orderCompile($order);

            OrderLog::create([
                'order_id'    => $order->order_id,
                'description' => 'Обновил товар ' . $newSource['DEVICE']['deviceName'] . ' ' . $newSource['DEVICE']['caseName'],
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);
        });
    }

    public function cartSetProductUpdate(Request $request)
    {
        $cartSetProduct = CartSetProduct::find($request['cartSetProductId']);
        \DB::transaction(function () use ($request, $cartSetProduct) {
            $description = '';
            switch ($request['type'])
            {
                case 'offer':
                    $cartSetProduct->offer_id = intval($request['selectId']);
                    $description = 'Обновил свойство футболки "' . $cartSetProduct->offer->product->title . '"';
                    break;
                case 'size':
                    $cartSetProduct->size = intval($request['selectId']);
                    $description = 'Обновил размер футболки "' . $cartSetProduct->offer->product->title . '"';
                    break;
                case 'print':
                    $cartSetProduct->print = intval($request['selectId']);
                    $description = 'Обновил тип печати футболки "' . $cartSetProduct->offer->product->title . '"';
                    break;
            }
            $cartSetProduct->save();
            OrderLog::create([
                'order_id'    => intval($request['orderId']),
                'description' => $description,
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);

        });

        return ['answer' => 'success'];

    }

    public function ajaxUpdateDeliveryTime(Request $request)
    {
        $cartSet = CartSet::find(['cart_set_id' => $request['cartId']]);
        $cart = $cartSet[0]->cart;
        $order = $cart->order;
        $order->delivery_time_from = $request['time'];
        $order->delivery_time_to = $request['time_to'];
        $order->save();
        return $order;
    }

    public function ajaxUpdatePrintinfo(Request $request)
    {
        $cartSetProduct = CartSetProduct::find($request['cartSetProductId']);
        \DB::transaction(function () use ($request, $cartSetProduct) {
            $description = '';
            switch($request['variable'])
            {
                case 'date-send':
                    $cartSetProduct->date_send = $request['date'];
                    $description = 'Обновил дату отправки на печать товара "' . $cartSetProduct->offer->product->id;
                    break;
                case 'supposed-date':
                    $cartSetProduct->supposed_date = $request['date'];
                    $description = 'Обновил предполагаемую дату забора товара "' . $cartSetProduct->offer->product->id;
                    break;
                case 'date-back':
                    $cartSetProduct->date_back = $request['date'];
                    $description = 'Обновил дату забора из печати товара "' . $cartSetProduct->offer->product->id;
                    break;
                case 'print-status':
                    $cartSetProduct->print_status_id = $request['selectId'];
                    $description = 'Обновил статус печати товара "' . $cartSetProduct->offer->product->id;
                    break;
            }

            $cartSetProduct->save();

            OrderLog::create([
                'order_id'    => intval($request['orderId']),
                'description' => $description,
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);
        });

        return ['answer' => 'success'];
    }

    public function ajaxCaseUpdatePrintInfo(Request $request)
    {
        $cartSetCase = CartSetCase::find(['cart_set_id' => $request->input('cartSetCase')])->first();

        \DB::transaction(function () use ($request, &$cartSetCase) {
            $description = '';

            switch($request['variable'])
            {
                case 'date-send':
                    $cartSetCase->date_send = $request->input('date');
                    $description = 'Обновил дату отправки на печать товара ' .  strtolower($cartSetCase->casey->case_caption) . ' чехол на ' . $cartSetCase->device->device_caption;
                    break;
                case 'supposed-date':
                    $cartSetCase->date_supposed = $request['date'];
                    $description = 'Обновил предполагаемую дату забора товара ' .  strtolower($cartSetCase->casey->case_caption) . ' чехол на ' . $cartSetCase->device->device_caption;
                    break;
                case 'date-back':
                    $cartSetCase->date_back = $request['date'];
                    $description = 'Обновил дату забора из печати товара "' .  strtolower($cartSetCase->casey->case_caption) . ' чехол на ' . $cartSetCase->device->device_caption;
                    break;
                case 'print-status':
                    $cartSetCase->print_status_id = $request['selectId'];
                    $description = 'Обновил статус печати товара "' .  strtolower($cartSetCase->casey->case_caption) . ' чехол на ' . $cartSetCase->device->device_caption;
                    break;
            }
            $cartSetCase->save();

            OrderLog::create([
                'order_id'    => intval($request['orderId']),
                'description' => $description,
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);
        });

        return ['answer' => 'success'];
    }

    public function ajaxEditItemCost(Request $request)
    {
        $id = $request->input('id');
        $cost = $request->input('cost');
        $old_cost = $request->input('old_cost');
        $type = $request->input('type');

        \DB::transaction(function () use ($id, $cost, $old_cost, $type) {
            if ($type == 'product')
            {
                $cartSetProduct = CartSetProduct::find($id);
                $cartSetProduct->item_cost = $cost;
                $cartSetProduct->save();
                $cartSetProduct->cart->order->order_amount -= ($cartSetProduct->item_count * $old_cost);
                $cartSetProduct->cart->order->order_amount += ($cartSetProduct->item_count * $cost);
                $cartSetProduct->cart->order->save();

                OrderLog::create([
                    'order_id'    => (int)$cartSetProduct->cart->order->order_id,
                    'description' => 'Обновил цену товара '. $cartSetProduct->offer->product->id . ' с ' . $old_cost . ' на ' . $cost,
                    'short_code'  => OrderLog::CUSTOM_CODE,
                ]);
            }
            else
            {
                $cartSetCase = CartSetCase::where('cart_set_id', '=', $id)->get()[0];
                $cartSetCase->item_cost = $cost;
                $cartSetCase->save();
                $cartSetCase->cart->order->order_amount -= ($cartSetCase->item_count * $old_cost);
                $cartSetCase->cart->order->order_amount += ($cartSetCase->item_count * $cost);
                $cartSetCase->cart->order->save();

                OrderLog::create([
                    'order_id' => (int)$cartSetCase->cart->order->order_id,
                    'description' => 'Обновил цену товара '. $cartSetCase->offer->product->id . ' с ' . $old_cost . ' на ' . $cost,
                    'short_code' => OrderLog::CUSTOM_CODE,
                ]);
            }
        });

        return ['answer' => 'success'];
    }

    public function ajaxEditItemCount(Request $request)
    {
        $id = $request->input('id');
        $count = $request->input('count');
        $old_count = $request->input('old_count');
        $type = $request->input('type');

        \DB::transaction(function() use ($id, $count, $old_count, $type) {
            if ($type == 'product')
            {
                $cartSetProduct = CartSetProduct::find($id);
                $cartSetProduct->item_count = $count;
                $cartSetProduct->save();
                $cartSetProduct->cart->order->order_amount -= ($old_count * $cartSetProduct->item_cost);
                $cartSetProduct->cart->order->order_amount += ($cartSetProduct->item_cost * $cartSetProduct->item_count);
                $cartSetProduct->cart->order->save();

                OrderLog::create([
                    'order_id' => (int) $cartSetProduct->cart->order->order_id,
                    'description' => 'Обновил кол-во товара ' . $cartSetProduct->offer->product->id . ' с ' . $old_count . ' на ' . $count,
                    'short_code' => OrderLog::CUSTOM_CODE,
                ]);
            }
            else
            {
                $cartSetCase = CartSetCase::where('cart_set_id', '=', $id)->get()[0];
                $cartSetCase->item_count = $count;
                $cartSetCase->save();
                $cartSetCase->cart->order->order_amount -= ($old_count * $cartSetCase->item_cost);
                $cartSetCase->cart->order->order_amount += ($cartSetCase->item_count * $cartSetCase->item_cost);
                $cartSetCase->cart->order->save();

                OrderLog::create([
                    'order_id' => (int) $cartSetCase->cart->order->order_id,
                    'description' => 'Обновил кол-во товара ' . $cartSetCase->offer->product->id . ' с ' . $old_count . ' на ' . $count,
                    'short_code' => OrderLog::CUSTOM_CODE,
                ]);
            }

        });

        return ['answer' => 'success'];
    }

    public function ajaxEditDeliveryCost(Request $request)
    {
        $id = $request->input('id');
        $cost = $request->input('cost');
        $old_cost = $request->input('old_cost');

        $order = Order::find($id);

        \DB::transaction(function() use ($id, $cost, $old_cost, $order) {
            $order->delivery_amount = (int)$cost;
            $order->save();

            OrderLog::create([
                'order_id'    => (int)$order->order_id,
                'description' => 'Обновил стоимость доставки с ' . $old_cost . ' на ' . $cost,
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);
        });

        return ['answer' => 'success'];
    }

    public function ajaxSizeList(Request $request)
    {
        $result = ['size' => []];

        OptionValue::where(['option_id' => 8])->each(function(OptionValue $value) use (&$result) {
            $result['size'][] = [
                'id' => $value->id,
                'title' => $value->title,
                'value' => $value->value,
            ];
        });

        return $result;

    }

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxProductList(Request $request)
    {
        $result = ['items' => []];
        Product::hasOffer()
            ->active()
            ->where('name', 'ilike', '%' . strtolower($request->get('q', '')) . '%')
            ->orderBy('order')
            ->limit(30)
            ->each(function (Product $product) use (&$result) {
                $result['items'][] = [
                    'id'   => $product->getKey(),
                    'name' => $product->name,
                ];
            });

        return $result;
    }
    /**
     * @param Request $request
     * @return array
     */
    public function ajaxOfferList(Request $request)
    {
        $result = ['items' => []];

        Offer::whereProductId($request->get('product_id'))->where('active', true)->with(['optionValues' => function($q){
            return $q->select('title');
        }])->each(function (Offer $offer) use (&$result) {

            if($offer->optionValues){
                $result['items'][] = [
                    'id'            => $offer->id,
                    'option_values' => $offer->optionValues->implode('title',', '),
                ];
            }
        });
        
        return $result;
    }

    /**
     * Добавить товар в существующую корзину.
     *
     * @param Order $order
     * @param Cart $cartHelper
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function putCartSetProduct(Order $order, Cart $cartHelper, Request $request)
    {
        if ($order->cart) {
            if (true) {
                $product = Product::find($request->input('product_id'));
                //dump($product);
                /** @var Offer $offer */
                $offer = $product->offers()->first();
                $sku = $product->offers->first()->optionValues()->where(['option_id' => 1])->get() !== [] ?  Ohcasey::SKU_DEVICE : Ohcasey::SKU_PRODUCT;
                //dump($product);
                if ($sku === Ohcasey::SKU_DEVICE) {
                    $offer = null;
                    foreach ($product->offers as $o) {
                        if ($o->optionValues()->where(['option_id' => 1])->first() &&
                            $o->optionValues()->where(['option_id' => 3])->first()) {
                            $offer = $o;
                            break;
                        }
                    }
                    if ($offer && $product->background_id) {
                        $cartHelper->put(
                            $sku,
                            1,
                            ['DEVICE' => [
                                'device' => $offer->optionValues()->where(['option_id' => 1])->first()->value,
                                'color' => 0,
                                'casey' => $offer->optionValues()->where(['option_id' => 3])->first()->value,
                                'bg' => Background::find($product->background_id)->first()->name,
                            ]],
                            $offer->id,
                            $order->cart
                        );
                    }
                } else {
                    $cartHelper->put(
                        $sku,
                        1,
                        [],
                        $offer->id,
                        $order->cart
                    );
                }

                $order->order_amount = $order->cart->summary->amount;
                $order->save();

                OrderLog::create([
                    'order_id'    => $order->order_id,
                    'description' => 'Добавил товар ',
                    'short_code'  => OrderLog::CUSTOM_CODE,
                ]);
                return redirect()->to(\URL::previous() . '#cartSetCase');
            } else {
                /*return redirect()
                    ->to(\URL::previous())
                    ->withErrors(['Не указано количество или предложение']);*/
            }
        }

        return redirect()
            ->to(\URL::previous())
            ->withErrors(['У корзины нет заказа']);
    }
}