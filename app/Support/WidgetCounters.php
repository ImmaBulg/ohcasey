<?php

namespace App\Support;

use App\Models\Cart;
use App\Models\CartSet;
use App\Models\CartSetCase;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use DB;

/**
 * Class WidgetCounters
 * @package App\Support
 */
class WidgetCounters
{
    /**
     * @param Carbon|null $dateStart
     * @param Carbon|null $dateEnd
     * @return array
     */
    public function get(Carbon $dateStart = null, Carbon $dateEnd = null)
    {
        $cartTable        = with(new Cart())->getTable();
        $cartSetCaseTable = with(new CartSetCase())->getTable();
        $orderTable       = with(new Order())->getTable();

        $queries = [
            'new'          => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_NEW)->first()),
            'wait_payment' => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_WAIT_PAYMENT)->first()),
            'paid'         => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_PAID)->first()),
            'designing'    => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_DESIGNING)->first()),

            'printing'          => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_IN_PRINT)->first()),
            'print_with_defect' => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_PRINT_WITH_DEFECT)->first()),
            'print_pickup'      => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_PRINT_PICKUP)->first()),
            'print_sdek'        => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_PRINT_SDEK)->first()),
            'print_courier'     => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_PRINT_COURIER)->first()),

            'total'         => Order::query(),
            'no_connection' => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_NO_CONNECTION)->first()),
            'thinks'        => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_THINKS)->first()),
            'finished'      => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_FINISHED)->first()),
            'canceled'      => Order::byStatus(OrderStatus::withTrashed()->where('status_id', OrderStatus::STATUS_ID_CANCELED)->first()),

            'total_cases' => Order::query()->join($cartTable, function (JoinClause $clause) use ($cartTable, $orderTable) {
                    $clause->on($cartTable . '.cart_order_id', '=', $orderTable . '.order_id');
                })->join($cartSetCaseTable, function (JoinClause $clause) use ($cartTable, $cartSetCaseTable) {
                    $clause->on($cartSetCaseTable . '.cart_id', '=', $cartTable . '.cart_id');
                }),

            'avg_cases' => function ($queries) {
                if ($queries['total'] != 0) {
                   return round($queries['total_cases'] / $queries['total'], 2);
                }

                return 0;
            },

            'cart_count' => Cart::query(),

            'rate' => function ($queries) use ($dateStart, $dateEnd) {
                if ($queries['total'] != 0) {
                    return round($queries['cart_count'] / $queries['total'], 2);
                }

                return 0;
            },

            'abandoned' => function ($queries) use ($dateStart, $dateEnd) {
                if ($queries['cart_count'] != 0) {
                    return round((1 - $queries['total'] / $queries['cart_count']) * 100, 2);
                }

                return 0;
            },

            'sum' => function ($queries) use ($dateStart, $dateEnd) {
                $query = Order::select(\DB::raw('SUM(order_amount) as sum_order_amount'))
                    ->where(function (Builder $query) {
                        return $query->where('order_status_id', OrderStatus::STATUS_ID_FINISHED)
                            ->orWhere('order_status_id', OrderStatus::STATUS_ID_PAID);
                    });
                if ($dateStart && $dateEnd) {
                    $query->betweenDates($dateStart, $dateEnd);
                }

                return (float) data_get($query->first(), 'sum_order_amount', 0.0);
            },

            'avg_sum' => function ($queries) use ($dateStart, $dateEnd) {
                if ($queries['cart_count'] != 0) {
                    return round($queries['sum'] / $queries['cart_count'], 2);
                }

                return 0;
            },

        ];

        foreach ($queries as $key => $query) {
            if (is_callable($query)) {
                $queries[$key] = $query($queries);
            } else if ($dateStart && $dateEnd){
                /** @var Builder|Order $query */
                $queries[$key] = $query->betweenDates($dateStart, $dateEnd)->count();
            }
        }
		
		
		//Статистика по доставкам
		$queries['deliveries'] = DB::table('order')
							->select(DB::raw('COUNT (*)'), 'delivery.delivery_caption')
							->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
							->whereBetween('order_ts', [$dateStart, $dateEnd])
							->leftJoin('delivery', 'delivery.delivery_name', '=', 'order.delivery_name')
							->groupBy('delivery.delivery_caption')
							->get();
							
		//Выбор статистики заказов по городам
		$cities = DB::table('order')
			->select(DB::raw('COUNT (*)'), 'cdek_city.city_name_full', 'cdek_city.city_id')
			->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
			->whereBetween('order_ts', [$dateStart, $dateEnd])
			->leftJoin('cdek_city', 'cdek_city.city_id', '=', 'order.city_id')
			->groupBy('cdek_city.city_id')
			->get();
		$ordersByCities = [];
		$otherCitiesOrdersCount = 0;
		foreach($cities as $city){
			if($city->city_id == 44 || $city->city_id == 137){
				$ordersByCities[!empty($city->city_name_full) ? $city->city_name_full : 'Не указан город'] = $city;
			}else{
				$otherCitiesOrdersCount += $city->count;
				$ordersByCities['Другие'][!empty($city->city_name_full) ? $city->city_name_full : 'Не указан город'] = $city;
			}
		}
		ksort($ordersByCities);
		if(!empty($ordersByCities['Другие'])){
			ksort($ordersByCities['Другие']);
			$ordersByCities['Другие']['count'] = $otherCitiesOrdersCount;
		}
		
		$queries['ordersByCities'] = $ordersByCities;
		
		//Статистика по корзинам
		$cartCount = DB::table('cart')
			->select(DB::raw('COUNT (cart.*) as count'))
			->whereBetween('cart_ts', [$dateStart, $dateEnd])
			->first();
		$cartCount = isset($cartCount->count) ? $cartCount->count : 0;
		$queries['cartCount'] = $cartCount;
			
		$cartSetCaseCount = DB::table('cart_set_case')
			->select(DB::raw('COUNT (cart_set_case.*) as count'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set_case.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$cartSetCaseCount = isset($cartSetCaseCount->count) ? $cartSetCaseCount->count : 0;
		
		$queries['casesPerCart'] = $cartSetCaseCount > 0 ? round($cartCount / $cartSetCaseCount, 2) : 0;
		
		$cartInProductionCount = DB::table('cart')
			->select(DB::raw('COUNT (cart.*) as count'))
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$cartInProductionCount = isset($cartInProductionCount->count) ? $cartInProductionCount->count : 0;
		$queries['cartInProductionCount'] = $cartInProductionCount;
		$caseInProductionCount = DB::table('cart_set_case')
			->select(DB::raw('SUM(cart_set_case."item_count") as count'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set_case.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$caseInProductionCount = isset($caseInProductionCount->count) ? $caseInProductionCount->count : 0;
		$queries['caseInProductionCount'] = $caseInProductionCount;
		$queries['casesPerCartInProduction'] = $cartInProductionCount > 0 ? round($caseInProductionCount / $cartInProductionCount, 2) : 0;
		
		$caseSumInCarts = DB::table('cart_set')
			->select(DB::raw('SUM(cart_set.item_count * cart_set.item_cost) as sum'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$queries['checkPerCart'] = !empty($caseSumInCarts->sum) ? round($caseSumInCarts->sum / $cartCount, 2) : 0;
		
		$caseSumInCartsInProduction = DB::table('cart_set')
			->select(DB::raw('SUM(cart_set.item_count * cart_set.item_cost) as sum'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$queries['checkPerCartInProduction'] = !empty($caseSumInCartsInProduction->sum) ? round($caseSumInCartsInProduction->sum / $cartCount, 2) : 0;
		
		
		$ordersCount = DB::table('order')
			->select(DB::raw('COUNT (*) as count'))
			->whereBetween('order_ts', [$dateStart, $dateEnd])
			->first();
		$queries['basketCoversion'] = !empty($ordersCount->count) ? round($ordersCount->count / $cartCount, 2) * 100 : 0;
		
		$ordersCountInProduction = DB::table('order')
			->select(DB::raw('COUNT (*) as count'))
			->whereBetween('order_ts', [$dateStart, $dateEnd])
			->whereIn('order.order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])
			->first();
		$queries['ordersCountInProduction'] = $ordersCountInProduction->count; 
		$queries['basketCoversionInProduction'] = !empty($ordersCountInProduction->count) ? round($ordersCountInProduction->count / $cartCount, 2) * 100 : 0;
		
		
		
		//Статистика по выручке
		$orders = Order::whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->with(['cart', 'cart.cartSetCase', 'cart.cartSetProducts', 'cart.promotionCode']);
			//->get();
			
		
		
		$orderSum = 0; //Сумма всех заказов 
		$orderSumInProduction = 0; // Сумма заказов со статусами макет, печать, доставлен
		$proceeds = 0; //Выручка - сумма заказов (макет, печать, доставлен) за минусом стоимости доставки, стоимости чехла и печати.
		
		$categories = [
			'silicone' => [36],
			'glitter' => [58],
		];
		
		$orderCountByDevice = []; //Количество заказов по типу устройства
		$orerCountByCaseType = []; //Количество заказов по типу чехла
		//ВСЕГО ЧЕХЛОВ
		$setCaseSum = DB::table('cart_set_case')
			->select(DB::raw('SUM(cart_set_case.item_count) as sum'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set_case.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->first();
		$allCasesSum = $setCaseSum->sum;
		$queries['allCasesSum'] = $allCasesSum;
		//ЧЕХЛОВ ЗАВЕРШЕНО
		$setCaseFinishedSum = DB::table('cart_set_case')
			->select(DB::raw('SUM(cart_set_case.item_count) as sum'))
			->leftJoin('cart', 'cart.cart_id', '=', 'cart_set_case.cart_id')
			->leftJoin('order', 'order.order_id', '=', 'cart.cart_order_id')
			->whereBetween('order.order_ts', [$dateStart, $dateEnd])
			->where('order.order_status_id', OrderStatus::STATUS_ID_FINISHED)
			->first();
		$queries['caseFinishedSum'] = $setCaseFinishedSum->sum;
		
		//Старые пользователи
		$oldUsers = DB::table('order')
			->select('client_phone')
			->groupBy('order.client_phone')
			->havingRaw('COUNT(*) > 1')
			->get();
		
		$phones = [];
		foreach($oldUsers as $oldUser){
			$phones[] = mb_substr(str_replace(['+', '-', ' ', '(', ')', '_'], '', $oldUser->client_phone), 1);
		}
		
		$promoCodesCounts = [];
		$orderUtm = []; //Utm-переходы
		$oldUsersCount = 0; //Количество старых польователи(больше одного заказа)
		$newUsersCount = 0; //Количество новых пользователи
		$ordersWithTextCount = 0; //Заказы где был использован шрифт
		$ordersWithSmileCount = 0; //Заказы где были использованы смайлы
		$defaultProductsCount = 0;
		$orders->chunk(300, function ($chunk) use (&$orderSum, &$defaultProductsCount, &$ordersWithSmileCount, &$ordersWithTextCount, &$newUsersCount, &$oldUsersCount, &$orderUtm, &$promoCodesCounts, &$phones, &$orderSumInProduction, &$proceeds, &$categories){
			foreach($chunk as $order){
				$orderSum += (int)$order->order_amount;
                //dump($orderSum);
				if(isset($order->cart) && !$order->cart->cartSetProducts->isEmpty()){
					foreach($order->cart->cartSetProducts as $setProduct){
						$defaultProductsCount += $setProduct->item_count;
					}
				}
				if(in_array($order->order_status_id, [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED])){
					//Подсчет промо-кодов
					$promoCode = $order->cart->promotionCode;
					//dump($order->cart);
					if($promoCode){
						if(isset($promoCodesCounts[$promoCode->code_value])){
							$promoCodesCounts[$promoCode->code_value]['count']++;
						}else{
							$promoCodesCounts[$promoCode->code_value] = [
								'count' => 1,
								'name' => $promoCode->code_name
							];
						}
					}
					//Подсчет utm переходов
					if(!empty($order->utm)){
						$utms = explode( ', ', $order->utm);
						foreach($utms as $utm){
							$utmParts = explode('=', $utm);
							if(strtolower($utmParts[0]) != 'utm_source') continue;
							$orderUtm[$utmParts[1]] = isset($orderUtm[$utmParts[1]]) ? $orderUtm[$utmParts[1]] + 1 : 1;
						}
					}
					//Подсчет количества старых\новых пользователей
					if(in_array(mb_substr(str_replace(['+', '-', ' ', '(', ')', '_'], '', $order->client_phone), 1), $phones)){
						$oldUsersCount++;
					}else{
						$newUsersCount++;
					}
					$orderSumInProduction += (int)$order->order_amount;
					$proceeds += (int)$order->order_amount;
					//Вычитаем значение скидки
					$proceeds -= $order->discont_amount;
					//Вычитаем стоимость изготовления чехлов из конструктора
					foreach($order->cart->cartSetCase as $case){
						//Подсчет заказов с надписями/смайлами
						if(!empty($case->item_source['TEXT'])){
							$ordersWithTextCount++;
						}
						if(!empty($case->item_source['SMILE'])){
							$ordersWithSmileCount++;
						}
						$device = $case->item_source['DEVICE']['device'];
						$casey = $case->item_source['DEVICE']['casey'];
						$orderCountByDevice[$device] =  isset($orderCountByDevice[$device]) ? $orderCountByDevice[$device] + $case->item_count : $case->item_count;
						$orerCountByCaseType[$casey] = isset($orerCountByCaseType[$casey]) ? $orerCountByCaseType[$casey] + $case->item_count : $case->item_count;
                        $minusPrice = [];
                        if (Carbon::parse($order->order_ts)->gt(Carbon::parse('2018-02-14'))) {
                            $minusPrice = [265, 350, 350, 350, 650, 200, 50];
                        } else if (Carbon::parse($order->order_ts)->gt(Carbon::parse('2017-09-01'))) {
                            $minusPrice = [400, 500, 500, 500, 650, 200, 0];
                        } else {
                            $minusPrice = [500, 600, 600, 600, 650, 200, 0];
                        }
                        //dump($minusPrice);
                        if (in_array($case->item_source['DEVICE']['device'], [
                                'iphone4',
                                'iphone4s',
                                'iphone5',
                                'iphone5s',
                                'iphone6',
                                'iphone6s',
                                'iphone7',
                                'iphone8',
                                'iphonex',
                                'sgs6',
                                'sgs7',
                            ]) && in_array($case->item_source['DEVICE']['casey'], ['silicone', 'plastic', 'softtouch'])) {
                            //Phone 4, 4S, 5, 5S, 6, 6S, 7, 8, X, а так же Samsung S6, S7: силикон, пластик черный и полупрозрачный
                            $proceeds -= $minusPrice[0] * $case->item_count;
                        } elseif (in_array($case->item_source['DEVICE']['device'], [
                                'iphone4',
                                'iphone4s',
                                'iphone5',
                                'iphone5s',
                                'iphone6',
                                'iphone6s',
                                'iphone7',
                                'iphone8',
                                'iphonex',
                                'sgs6',
                                'sgs7',
                                'sgs6e',
                                'sgs7e',
                            ]) && in_array($case->item_source['DEVICE']['casey'], ['glitter', 'glitter_1', 'glitter_2', 'glitter_3', 'glitter_4'])) {
                            //Эти же модели, а так же Samsung S7 Edge, S6 Edge: глитер любой
                            $proceeds -= $minusPrice[1] * $case->item_count;
                        } elseif (in_array($case->item_source['DEVICE']['device'], ['sgs6e', 'sgs7e']) && in_array($case->item_source['DEVICE']['casey'], ['silicone', 'plastic', 'softtouch'])) {
                            //S7 Edge, S6 Edge: силикон, пластик черный и полупрозрачный
                            $proceeds -= $minusPrice[2] * $case->item_count;
                        } elseif (in_array($case->item_source['DEVICE']['device'], [
                                'iphone6plus',
                                'iphone7plus',
                                'iphone8plus'
                            ]) && in_array($case->item_source['DEVICE']['casey'], [
                                'glitter',
                                'glitter_1',
                                'glitter_2',
                                'glitter_3',
                                'glitter_4',
                                'silicone',
                                'plastic',
                                'softtouch'
                            ])) {
                            //Phone 6+, 7+, 8+: силикон, пластик черный и полупрозрачный, глитер любой
                            $proceeds -= $minusPrice[3] * $case->item_count;
                        } elseif ($case->item_source['DEVICE']['casey'] == 'silicone') {
                            //Одноцветные силиконовые чехлы
                            $proceeds -= $minusPrice[4] * $case->item_count;
                        } elseif (in_array($case->item_source['DEVICE']['casey'], ['glitter', 'glitter_1', 'glitter_2', 'glitter_3', 'glitter_4'])) {
                            //Одноцветные силиконовые чехлы
                            $proceeds -= $minusPrice[4] * $case->item_count;
                        }

                        //иллюстрация “Сердце мазками
                        if ($case->item_source['DEVICE']['bg'] == 'serdtse2.png') {
                            $proceeds -= $minusPrice[6] * $case->item_count;
                        }
						
					}
                    $proceeds -= $order->discount_amount;
					//Вычитаем стоимость изготовления чехлов из каталога
					foreach($order->cart->cartSetProducts as $case){
						$device = isset($case->item_source['DEVICE']['device']) ? $case->item_source['DEVICE']['device'] : 'Silicone one color cases';
						$orderCountByDevice[$device] = isset($orderCountByDevice[$device]) ? $orderCountByDevice[$device] + 1 : 1;
						//var_dump($case->offer->product->firstCategory());
                        if ($case->offer->product)
						    $category = $case->offer->product->firstCategory();
                        else
                            $category = null;
						if(!$category){
							continue;
						}
						$categoryId = $category->id;
						$parentCategoryId = null;
						if(!empty($case->offer->product) && !empty($case->offer->product->firstCategory()) && !empty($case->offer->product->firstCategory()->selfParent)){
							$parentCategoryId = $case->offer->product->firstCategory()->selfParent->id;
						}
						if(in_array($categoryId, $categories['silicone']) || in_array($parentCategoryId, $categories['silicone'])){
							$proceeds -= 650 * $case->item_count;
						}elseif(in_array($categoryId, $categories['glitter']) || in_array($categoryId, $categories['glitter'])){
							$proceeds -= 200 * $case->item_count;
						}
					}
					//Вычитаем стоимость доставки, если есть промокод на бесплатную доставку
					if($order->cart->promotionCode && $order->cart->promotionCode->code_discount == 'D'){
						$proceeds -= $order->delivery_amount;
					}
				}
			};

		});

		$queries['proceeds'] = [
			'orderSum' => $orderSum,
			'orderSumInProduction' => $orderSumInProduction,
			'pure' => $proceeds,
		];
		
		$queries['defaultProductsCount'] = $defaultProductsCount;
		
		$queries['orderCountByDevice'] = $orderCountByDevice;
		$queries['orerCountByCaseType'] = $orerCountByCaseType;
		
		
		$queries['oldUsersCount'] = $oldUsersCount;
		$queries['newUsersCount'] = $newUsersCount;
		
		$queries['orderUtm'] = $orderUtm;
		$queries['promoCodesCounts'] = $promoCodesCounts;
		
		$queries['ordersWithTextCount'] = $ordersWithTextCount;
		$queries['ordersWithSmileCount'] = $ordersWithSmileCount;

        return $queries;
    }
}