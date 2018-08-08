<?php

namespace App\Http\Controllers;

use App\Models\Background;
use App\Models\BackgroundGroup;
use App\Models\Cart as CartModel;
use App\Models\Cart;
use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Font;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PromotionCode;
use App\Models\Smile;
use App\Models\Delivery;
use App\Models\SmileGroup;
use App\Models\Shop\OptionValue;
use App\Ohcasey\FontInfo;
use App\Ohcasey\Ohcasey;
use App\Support\OrderApplyRequestFilters;
use App\Support\WidgetCounters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Input;
use Response;

/**
 * Class Admin
 * @package App\Http\Controllers
 */
class Admin extends Controller
{
    /**
     * @param Request $request
     * @param WidgetCounters $widgetCounters
     * @throws
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboards(Request $request, WidgetCounters $widgetCounters)
    {
        $dateStart = Carbon::parse($request->get('start', 'NOW'))->startOfDay();
        $dateEnd   = Carbon::parse($request->get('end', 'NOW'))->endOfDay();

        return view('admin.dashboards')->with([
            'counters'  => $widgetCounters->get($dateStart, $dateEnd),
            'dateStart' => $dateStart,
            'dateEnd'   => $dateEnd,
        ]);
    }

    /**
     * Main page
     * @param Request $request
     * @param OrderApplyRequestFilters $processRequestFilterData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, OrderApplyRequestFilters $processRequestFilterData, WidgetCounters $widgetCounters)
    {
        $end = Carbon::parse($request->input('f_date_end', 'NOW'))->startOfDay();
        $endDate = $end->copy()->addDay();

        $start = Carbon::parse($request->input('f_date_start'))->startOfDay();	

        /** @var Carbon $startOrder */
        $startOrder = $request->has('f_date_start') ? $start->copy() : $endDate->copy()->subDays(32);
        $startStat = $request->has('f_date_start') ? $start->copy() : $endDate->copy()->subDays(32);
		
/*        if ($endDate->diffInDays($startStat, true) > 32) {
            $startStat = $endDate->copy()->subDays(32);
        }*/
		
		$params = $request->except('page');
		if(!isset($params['f_date_start'])){
			$request['f_date_start'] = $startOrder->format('Y-m-d');
		}
		if(!isset($params['f_date_end'])){
			$request['f_date_end'] = $endDate->format('Y-m-d');
		}
		if(isset($params['f_date_start']) && $params['f_date_start'] === ""){
			$startOrder = $endDate->copy()->subYears(3);
		}
		
		//dump($params);
		
		/*\DB::listen(function ($sql) {
			dump($sql->time);
		});*/


        $orders = Order::with([
            'status',
            'cart',
            'cart.cartSetCase',
            'cart.cartSetProducts',
            'specialItems',
            'payments',
        ])->orderBy('order_id', 'desc');

        if ($request->input('f_client') || $request->input('f_id') || $request->input('f_phone'))
        {
            $request['f_date_start'] = '';
            $request['f_date_end'] = '';
            $end = Carbon::parse($request->input('f_date_end', 'NOW'))->startOfDay();
            $endDate = $end->copy()->addDay();

            $orders = $processRequestFilterData->applyFilter($orders, $request, $endDate);
        }
        else
            $orders = $processRequestFilterData->applyFilter($orders, $request, $endDate, $startOrder);
		
		$casesSum = 0;
		$productsSum = 0;
		$ordersSum = 0;
		$orders->chunk(200, function ($ords) use (&$casesSum, &$productsSum, &$ordersSum){
			$ords->each(function ($o, $k) use (&$casesSum, &$productsSum, &$ordersSum) {
				$ordersSum += $o->order_amount;
				if($o->cart && $o->cart->cartSetCase()->sum('item_count') != null){
					$casesSum += $o->cart->cartSetCase()->sum('item_count');
				}
				if($o->cart && $o->cart->cartSetProducts()->sum('item_count') != null){
					$productsSum += $o->cart->cartSetProducts()->sum('item_count');
				}
			});
		});
		
		
        $statistics = $this->calculateStatistics($end, $endDate, $startStat, $request->get('with_trashed', false));
		
		
		$orderPhones = DB::table('order')
			->select('client_phone')
			->groupBy('order.client_phone')
			->havingRaw('COUNT(*) > 1')
			->get();
		$phones = [];
		foreach($orderPhones as $order){
			$phones[] = mb_substr(str_replace(['+', '-', ' ', '(', ')', '_'], '', $order->client_phone), 1);
		}


        return view('admin.main', [
            'startOrder'       => $startOrder,
            'startStat'        => $startStat,
            'end'              => $end,
            'start'            => $start,
            'statCart'         => $statistics['statCart'],
            'statOrder'        => $statistics['statOrder'],
            'statSuccessOrder' => $statistics['statSuccessOrder'],
            'orders'           => $orders->paginate(100)->appends($params),
            'statuses'         => OrderStatus::orderBy('sort', 'DESC')->get(),
            'phones'         => $phones,
            'counters'  => $widgetCounters->get($startStat, $endDate),
			'casesSum' => $casesSum,
			'productsSum' => $productsSum,
			'ordersSum' => $ordersSum,
        ]);
    }

    /**
     * Графики.
     *
     * @param Carbon $end
     * @param Carbon $endDate
     * @param Carbon|null $startStat
     * @param bool $withTrashed
     * @return array
     */
    protected function calculateStatistics(Carbon $end, Carbon $endDate, Carbon $startStat = null, $withTrashed = false)
    {
        $withTrashed = ($withTrashed ? '' : 'AND deleted_at IS NULL');

        $statCart = collect(DB::select("
            SELECT COUNT(*) as count,
              EXTRACT('day' FROM DATE_TRUNC('day', ?::date) - DATE_TRUNC('day', cart_ts)) as ago
            FROM cart
            WHERE cart_ts >= ? AND cart_ts < ?
            GROUP BY DATE_TRUNC('day', cart_ts)", [
                $end->format('Y-m-d'),
                $startStat->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]
        ))->keyBy('ago');

        $statOrder = collect(DB::select('
            SELECT COUNT(*) as count,
                EXTRACT(\'day\' FROM DATE_TRUNC(\'day\', ?::date) - DATE_TRUNC(\'day\', order_ts)) as ago
            FROM "order"
            WHERE order_ts >= ?
                AND order_ts < ?
                ' . $withTrashed . '
            GROUP BY DATE_TRUNC(\'day\', order_ts)',
            [
                $end->format('Y-m-d'),
                $startStat->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]
        ))->keyBy('ago');

        $statSuccessOrder = collect(DB::select('
            SELECT COUNT(*) as count,
                EXTRACT(\'day\' FROM DATE_TRUNC(\'day\', ?::date) - DATE_TRUNC(\'day\', order_ts)) as ago
            FROM "order"
            WHERE order_ts >= ?
                AND order_ts < ?
                AND order_status_id IN (
                    SELECT status_id
                    FROM order_status
                    WHERE status_success
                )
                ' . $withTrashed . '
            GROUP BY DATE_TRUNC(\'day\', order_ts)',
            [
                $end->format('Y-m-d'),
                $startStat->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]
        ))->keyBy('ago');

        return [
            'statCart'         => $statCart,
            'statOrder'        => $statOrder,
            'statSuccessOrder' => $statSuccessOrder,
        ];
    }
    /**
     * Background page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bg(Request $request) {
        $backgrounds = Background::orderBy('order', 'desc')->with('backgroundGroups');

        if ($request->has('cat')) {
            $cats = $request->input('cat');
            $backgrounds->where(function ($query) use ($cats) {
                $catNames = [];
                $withoutCat = false;
                foreach ($cats as $cat) {
                    if ($cat == '-') {
                        $withoutCat = true;
                    } else {
                        $catNames[] = $cat;
                    }
                }
                if ($catNames) {
                    $query->whereHas('backgroundGroups', function ($query) use ($catNames) {
                        return $query->whereIn('name', $catNames);
                    });
                }
                if ($withoutCat) {
                    $query->orWhereDoesntHave('backgroundGroups');
                }
                return $query;
            });
        }

        return view('admin.backgrounds', [
            'categories'  => BackgroundGroup::orderBy('order')->orderBy('name')->get(),
            'backgrounds' => $backgrounds->paginate(60),
        ]);
    }

    /**
     * Background stats
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bgStat(Request $request) {
        $models = OptionValue::where('option_id', 1)->get();
        $start = (new Carbon($request->input('f_date_start'), 'Europe/Moscow'))->startOfDay();
        if (! $request->has('f_date_start')) {
            $start->subYears(100);
        }
        $end = (new Carbon($request->input('f_date_end'), 'Europe/Moscow'))->startOfDay()->addDay();

		$cartsCollection = Cart::with(['order', 'cartSetCase'])
			->whereHas('order', function ($query) use ($start, $end) {
				$query->whereIn('order_status_id', [OrderStatus::STATUS_ID_IN_PRINT, OrderStatus::STATUS_ID_DESIGNING, OrderStatus::STATUS_ID_FINISHED]);
				$query->whereBetween('order_ts', [$start, $end]);
			});
			
		
			
        $backgrounds = [];
        $request_model = $request->input('option_model');
		$cartsCollection->chunk(100, function ($carts) use (&$backgrounds, $request_model) {
			if ($request_model)
            {
                foreach($carts as $cart){
                    foreach($cart->cartSetCase as $setCase){
                        if(!empty($setCase->item_source['DEVICE']['bg']) && $setCase->device_name === $request_model){
                            if(isset($backgrounds[$setCase->item_source['DEVICE']['bg']])){
                                $backgrounds[$setCase->item_source['DEVICE']['bg']] += $setCase->item_count;
                            }else{
                                $backgrounds[$setCase->item_source['DEVICE']['bg']] = $setCase->item_count;
                            }
                        }
                    }
                }
                    
            }
            else
            {
                foreach($carts as $cart){
                    foreach($cart->cartSetCase as $setCase){
                        if(!empty($setCase->item_source['DEVICE']['bg'])){
                            if(isset($backgrounds[$setCase->item_source['DEVICE']['bg']])){
                                $backgrounds[$setCase->item_source['DEVICE']['bg']] += $setCase->item_count;
                            }else{
                                $backgrounds[$setCase->item_source['DEVICE']['bg']] = $setCase->item_count;
                            }
                        }
                    }
                }
            }
		});
		
					//->whereBetween('cart.order_ts', [$start, $end])->get()
		arsort($backgrounds);

        return view('admin.backgrounds_stats', ['backgrounds' => $backgrounds, 'models' => $models]);
    }

    /**
     * Add postfix if file already exists
     * @param $path
     * @param $fname
     * @return string
     */
    private function getFreeFilename($path, $fname) {
        // Get name
        $fname = explode('.', $fname);
        foreach ($fname as &$n) {
            $n = str_slug($n);
        }
        $fname = implode('.', $fname);

        $name = $fname;
        for ($i = 0; true; ++$i) {
            $name = ($i ? $i.'_' : '').$fname;
            if (file_exists($path.DIRECTORY_SEPARATOR.$name)) {
                continue;
            } else {
                break;
            }
        }
        return $name;
    }

    /**
     * Background upload
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bgUpload(Request $request)
    {
        $categories = array_merge(
            $request->input('cat', []),
            $request->input('cat_name') ? [$request->input('cat_name')] : []
        );

        if ($categories) {
            $categories = BackgroundGroup::whereIn('name', $categories)->get()->pluck('id')->toArray();
        } else {
            $categories = [];
        }

        foreach ($request->file('files') as $requestFile) {
            /** @var \Illuminate\Http\UploadedFile $requestFile */
            $name = $this->getFreeFilename(storage_path('app/bg'), $requestFile->getClientOriginalName());

            $background = new Background();
            $background->name = $name;
            $background->save();
            if ($categories) {
                $background->backgroundGroups()->sync($categories);
            }
            $requestFile->move(storage_path('app/bg'), $name);
        }

        $this->backgroundGroupsUpdate();

        return redirect('admin/bg');
    }

    /**
     * Destroy bg
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bgDestroy(Request $request) {
        $id = $request->input('id');
        if ($id) {
            \DB::transaction(function () use ($id) {
                Background::destroy($id);
                foreach (is_array($id) ? $id : [$id] as $_id) {
                    Storage::delete('bg/' . $_id);
                }
                $this->backgroundGroupsUpdate();
            });
        }
        return redirect('admin/bg');
    }

    /**
     * [bgSaveOrder description]
     * @param  Request $request 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bgSaveOrder(Request $request) {
        $params = $request->input();
        foreach(array_keys($params) as $param) {
            if ($param != '_token' && $param != '_method')
                Background::where('id', $param)->update(['order' => $params[$param]]);
        }
        return back()->withInput();
    }

    /**
     * Update bg
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bgUpdate(Request $request)
    {
        $backgroundNames = $request->input('id');
        $cat             = $request->input('cat');
        $catName         = $request->input('cat_name');

        $categories = array_merge(empty($cat) ? [] : $cat, $catName ? [$catName] : []);
        $categoryIds = [];
        if ($categories) {
            foreach ($categories as $categoryName) {
                $categoryIds[] = BackgroundGroup::firstOrCreate([
                    'name' => $categoryName
                ])->id;
            }
        }

        Background::whereIn('name', $backgroundNames)->each(function (Background $background) use ($categoryIds) {
            $background->backgroundGroups()->sync($categoryIds);
        });

        $this->backgroundGroupsUpdate();
        return redirect('admin/bg');
    }

    /**
     * Update groups from bg
     */
    private function backgroundGroupsUpdate()
    {
        BackgroundGroup::whereDoesntHave('backgrounds')->delete();
    }

    /**
     * Smile page
     * @param Request $r
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function smile(Request $r) {
        $smiles = Smile::orderBy('smile_ts', 'desc');
        if ($r->has('cat')) {
            $categories = [];
            $params = [];
            foreach ($r->input('cat') as $cat) {
                if ($cat == '-') {
                    $categories[] = 'smile_group is null';
                } else {
                    $categories[] = 'jsonb_exists(smile_group, ?)';
                    $params[] = $cat;
                }
            }
            $smiles->whereRaw(implode(' or ', $categories), $params);
        }
        return view('admin.smile', [
            'categories' => SmileGroup::orderBy('smile_group_name')->get(),
            'smiles' => $smiles->paginate(200)
        ]);
    }

    /**
     * Background upload
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function smileUpload(Request $request)
    {
        $categories = array_merge(
            $request->input('cat', []),
            $request->input('cat_name') ? [$request->input('cat_name')] : []
        );
        $categories = empty($categories) ? null : $categories;

        foreach ($request->file('files') as $file) {
            /** @var \Illuminate\Http\UploadedFile $file */
            // Get name
            $name = $this->getFreeFilename(storage_path('app/smile'), $file->getClientOriginalName());

            // Create new smile
            $smile = new Smile();
            $smile->smile_name = $name;
            $smile->smile_group = $categories;
            $smile->save();

            // Move file
            $file->move(storage_path('app/smile'), $name);
        }

        $this->smileGroupsUpdate();

        return redirect('admin/smile');
    }

    /**
     * Destroy smile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function smileDestroy(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            Smile::destroy($id);
            foreach (is_array($id) ? $id : [$id] as $_id) {
                Storage::delete('smile/'.$_id);
            }
            $this->smileGroupsUpdate();
        }

        return redirect('admin/smile');
    }

    /**
     * Update smile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function smileUpdate(Request $request)
    {
        $id      = $request->input('id');
        $cat     = $request->input('cat');
        $catName = $request->input('cat_name');

        $categories = array_merge(empty($cat) ? [] : $cat, $catName ? [$catName] : []);
        $categories = (empty($categories) ? null : $categories);

        Smile::whereIn('smile_name', $id)->update(['smile_group' => json_encode($categories)]);

        $this->smileGroupsUpdate();

        return redirect('admin/smile');
    }

    /**
     * Update groups from smile
     */
    private function smileGroupsUpdate()
    {
        DB::statement('truncate smile_group');
        DB::statement('
            insert into smile_group
            select distinct jsonb_array_elements_text(smile_group) from smile
        ');
    }

    /**
     * Font
     */
    public function font()
    {
        return view('admin.font',[
            'fonts' => Font::orderBy('font_name', 'asc')->get()
        ]);
    }

    /**
     * Font upload
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fontUpload(Request $request)
    {
        $file = $request->file('file');

        // Get name
        $name = $this->getFreeFilename(storage_path('app/fonts'), $file->getClientOriginalName());

        // Create new font
        $font = new Font();
        $font->font_name = $name;
        $font->font_caption = $request->get('font_caption') ?: (new FontInfo($file->getPathname()))->getFontName();
        $font->save();

        // Move file
        $file->move(storage_path('app/fonts'), $name);

        return redirect('admin/font');
    }

    /**
     * Destroy font
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fontDestroy(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            Font::destroy($id);
            foreach (is_array($id) ? $id : [$id] as $_id) {
                Storage::delete('fonts/'.$_id);
            }
        }
        return redirect('admin/font');
    }

    /**
     * Update font
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fontUpdate(Request $request)
    {
        $id = $request->input('id');
        $caption = $request->input('font_caption') ?: (new FontInfo(storage_path('app/fonts/' + $id)))->getFontName();
        Font::whereIn('font_name', $id)->update(['font_caption' => $caption]);
        return redirect('admin/font');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart(Request $request)
    {
        $end = (new Carbon($request->input('f_date_end'), 'Europe/Moscow'))->startOfDay();
        $start = (new Carbon($request->input('f_date_start'), 'Europe/Moscow'))->startOfDay();
        $startCart = $request->has('f_date_start') ? $start->copy() : null;

        // Orders
        $carts = CartModel::with(['cartSetCase']);
        if ($request->has('f_id'))    $carts->where('cart_id', '=', (int)$request->get('f_id'));
        if ($request->has('f_ip'))    $carts->where('cart_ip', 'like', '%'.$request->get('f_ip').'%');
        if ($request->has('f_order')) {
            if ($request->get('f_order') == '*') {
                $carts->where('cart_order_id', '!=', null);
            } else {
                $carts->where('cart_order_id', '=', (int)$request->get('f_order'));
            }
        }
        if ($startCart) {
            $carts->where('cart_ts', '>=', $startCart->format('Y-m-d'));
        }
        if ($end) {
            $carts->where('cart_ts', '<', $end->copy()->addDay()->format('Y-m-d'));
        }

        // List
        $orders = clone $carts;
        $orders->where('cart_order_id', '!=', null);

        return view('admin.cart', [
            'startCart' => $startCart,
            'end'       => $end,
            'carts'     => $carts->orderBy('cart_id', 'desc')->paginate(100),
            'orders'    => $orders->count()
        ]);
    }

    /**
     * Show compiled cart image
     * @param Ohcasey $o
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function cartImg(Ohcasey $o, $id)
    {
        $source = CartSetCase::findOrFail($id);
        $date = new Carbon($source->cart->cart_ts, 'Europe/Moscow');
        $file = 'carts/'.$date->format('Y/m/d').'/'.$id.'/img/'.$id.'.png';
        if (! Storage::exists($file)) {
            $im = $o->compile($source->item_source, Ohcasey::CASE_WIDTH_DEFAULT, Ohcasey::CASE_HEIGHT_DEFAULT);
            Storage::put($file, $im);
        }

        return response()->file(storage_path('app/'.$file), ['Content-Type' => 'image/png']);
    }

    /**
     * Show promotions
     */
    public function promotion(Request $request)
    {
        $query = PromotionCode::query();
        if ($request->has('f_code_id')) {
            $query->where('code_id', '=', (int)$request->input('f_code_id'));
        }
        if ($request->has('f_code_name')) {
            $query->where('code_name', '=', $request->input('f_code_name'));
        }
        if ($request->has('f_code_value')) {
            $query->where('code_value', '=', $request->input('f_code_value'));
        }
        if ($request->has('f_code_enabled')) {
            $query->where('code_enabled', '=', mb_strtolower($request->input('f_code_enabled')) == 'да');
        }

        return view('admin.promotion',[
            'codes' => $query->orderBy('code_id', 'desc')->paginate(100)
        ]);
    }

    /**
     * Create promotion
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promotionCreate(Request $request)
    {
        try {
            $promo = new PromotionCode();
            $promo->code_name = $request->input('code_name');
            $promo->code_value = $request->input('code_value');
            $promo->code_enabled = $request->input('code_enabled');
            $promo->code_discount = $request->input('code_discount_value', '').$request->input('code_discount_unit', '');
            $promo->code_valid_from = $request->input('code_valid_from') ?: null;
            $promo->code_valid_till = $request->input('code_valid_till') ?: null;
            $promo->code_cond_cart_count = $request->input('code_cond_cart_count') ?: null;
            $promo->code_cond_cart_amount = $request->input('code_cond_cart_amount') ?: null;
            $promo->save();

            $request->session()->flash('alert-success', 'Промокод добавлен');
        } catch (\Exception $e) {
            $request->session()->flash('alert-danger', 'Ошибка добавления: '.$e->getMessage());
        }

        return back();
    }

    /**
     * Destroy promotion
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promotionDestroy(Request $request, $id)
    {
        try {
            $carts = Cart::where('promotion_code_id', '=', $id)->count();
            if ($carts > 0) {
                throw new \Exception('Нельзя удалять промокод, если он использован хотя бы один раз');
            }

            $promo = PromotionCode::findOrFail($id);
            $promo->delete();
            $request->session()->flash('alert-success', 'Промокод удален');
        } catch (\Exception $e) {
            $request->session()->flash('alert-danger', 'Ошибка удаления: ' . $e->getMessage());
        }

        return back();
    }

    /**
     * Update promotion
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promotionUpdate(Request $request, $id) {
        try {
            $promo = PromotionCode::findOrFail($id);
            $promo->code_name = $request->input('code_name');
            $promo->code_value = $request->input('code_value');
            $promo->code_enabled = $request->input('code_enabled', false);
            $promo->code_discount = $request->input('code_discount_value', '').$request->input('code_discount_unit', '');
            $promo->code_valid_from = $request->input('code_valid_from') ?: null;
            $promo->code_valid_till = $request->input('code_valid_till') ?: null;
            $promo->code_cond_cart_count = $request->input('code_cond_cart_count') ?: null;
            $promo->code_cond_cart_amount = $request->input('code_cond_cart_amount') ?: null;
            $promo->save();

            $request->session()->flash('alert-success', 'Промокод обновлен');
        } catch (\Exception $e) {
            $request->session()->flash('alert-danger', 'Ошибка обновления'.$e->getMessage());
        }

        return back();
    }

    public function getCsv(Request $request)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request['startDate']);
        $endDate = Carbon::createFromFormat('Y-m-d', $request['endDate']);
        $result = [];
        /**/

        $headers = array(
            "Content-type" => "text/csv; charset=windows-1251",
            "Content-Disposition" => "attachment; filename=" . $startDate->format('d_m_Y') . '-' . $endDate->format('d_m_Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $cartSetProducts = CartSetProduct::whereBetween('date_send', [$request['startDate'], $request['endDate']])->get();
        $cartSetCases = CartSetCase::whereBetween('date_send', [$request['startDate'], $request['endDate']])->get();
        $result[0] = [
            'Заказ',
            'ID продукта в заказе',
            'Дизайн футболки',
            'Тип футболки',
            'Размер',
            'Количество',
            'Тип нанесения',
            'Статус заказа в печать',
            'Дата отправки в печать',
            'Предполагаемая дата забора',
            'Дата забора из печати',
        ];
        $i = 1;
        foreach ($cartSetProducts as $cartSetProduct) {
            $product = $cartSetProduct->offer->product;
            $order = $cartSetProduct->cart->order;
            if (isset($product) &&  $order != null && $cartSetProduct->print_status_id !== '69' && $cartSetProduct->print_status_id !== '75') {
                $result[$i] = [
                    $order->order_id,
                    $cartSetProduct->id,
                    $product->name,
                    isset($cartSetProduct->offer) ? $cartSetProduct->offer->optionValues[0]->title : '',
                    ($cartSetProduct->size) ? OptionValue::where(['id' => $cartSetProduct->size])->pluck('title')[0] : 'Не задана',
                    $cartSetProduct->item_count,
                    ($cartSetProduct->print) ? OptionValue::where(['id' => $cartSetProduct->print])->pluck('title')[0] : 'Не задан',
                    ($cartSetProduct->print_status_id) ? OptionValue::where(['id' => $cartSetProduct->print_status_id])->pluck('title')[0] : 'Не задан',
                    ($cartSetProduct->date_send) ? $cartSetProduct->date_send : 'Не задана',
                    ($cartSetProduct->supposed_date) ? $cartSetProduct->supposed_date : 'Не задана',
                    ($cartSetProduct->date_back) ? $cartSetProduct->date_back : 'Не задана',
                ];
                $i++;
            }
        }

        foreach ($cartSetCases as $cartSetCase) {
            $order = $cartSetCase->cart->order;
            if ($cartSetCase->print_status_id !== '69' && $cartSetCase->print_status_id !== '75') {
                $result[$i] = [
                    $order->order_id,
                    $cartSetCase->cart_set_id,
                    $cartSetCase->casey->case_caption . ' чехол на ' . $cartSetCase->device->device_caption,
                    '',
                    'Не задана',
                    $cartSetCase->item_count,
                    'Не задан',
                    ($cartSetCase->print_status_id) ? OptionValue::where(['id' => $cartSetCase->print_status_id])->pluck('title')[0] : 'Не задан',
                    ($cartSetCase->date_send) ? $cartSetCase->date_send : 'Не задана',
                    ($cartSetCase->supposed_date) ? $cartSetCase->supposed_date : 'Не задана',
                    ($cartSetCase->date_back) ? $cartSetCase->date_back : 'Не задана',
                ];
                $i++;
            }
        }

        $callback = function() use ($result)
        {
            //$FH = fopen('php://output', 'w');
            $str = '';
            foreach ($result as $i => $row) {
                foreach ($row as $r)
                    $str .= $r . ";";
                $str .= "\r\n";
                //fputcsv($FH, $row, ';');
            }
            $str = iconv("UTF-8", "WINDOWS-1251//IGNORE",  $str);
            file_put_contents('php://output', $str);
            //fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getBackCsv(Request $request)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request['startDate']);
        $endDate = Carbon::createFromFormat('Y-m-d', $request['endDate']);
        $result = [];
        /**/

        $headers = array(
            "Content-type" => "text/csv; charset=windows-1251",
            "Content-Disposition" => "attachment; filename=" . $startDate->format('d_m_Y') . '-' . $endDate->format('d_m_Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $cartSetProducts = CartSetProduct::whereBetween('date_send', [$request['startDate'], $request['endDate']])->get();
        $cartSetCases = CartSetCase::whereBetween('date_send', [$request['startDate'], $request['endDate']])->get();
        $result[0] = [
            'Заказ',
            'ID продукта в заказе',
            'Дизайн футболки',
            'Тип футболки',
            'Размер',
            'Количество',
            'Тип нанесения',
            'Статус заказа в печать',
            'Дата отправки в печать',
            'Предполагаемая дата забора',
            'Дата забора из печати',
        ];
        $i = 1;
        foreach ($cartSetProducts as $cartSetProduct) {
            $product = $cartSetProduct->offer->product;
            $order = $cartSetProduct->cart->order;
            if (isset($product) &&  $order != null && $cartSetProduct->print_status_id === '70') {
                $result[$i] = [
                    $order->order_id,
                    $cartSetProduct->id,
                    $product->name,
                    isset($cartSetProduct->offer) ? $cartSetProduct->offer->optionValues[0]->title : '',
                    ($cartSetProduct->size) ? OptionValue::where(['id' => $cartSetProduct->size])->pluck('title')[0] : 'Не задана',
                    $cartSetProduct->item_count,
                    ($cartSetProduct->print) ? OptionValue::where(['id' => $cartSetProduct->print])->pluck('title')[0] : 'Не задан',
                    ($cartSetProduct->print_status_id) ? OptionValue::where(['id' => $cartSetProduct->print_status_id])->pluck('title')[0] : 'Не задан',
                    ($cartSetProduct->date_send) ? $cartSetProduct->date_send : 'Не задана',
                    ($cartSetProduct->supposed_date) ? $cartSetProduct->supposed_date : 'Не задана',
                    ($cartSetProduct->date_back) ? $cartSetProduct->date_back : 'Не задана',
                ];
                $i++;
            }
        }

        foreach ($cartSetCases as $cartSetCase) {
            $order = $cartSetCase->cart->order;
            if ($cartSetCase->print_status_id === '70') {
                $result[$i] = [
                    $order->order_id,
                    $cartSetCase->cart_set_id,
                    $cartSetCase->casey->case_caption . ' чехол на ' . $cartSetCase->device->device_caption,
                    '',
                    'Не задана',
                    $cartSetCase->item_count,
                    'Не задан',
                    ($cartSetCase->print_status_id) ? OptionValue::where(['id' => $cartSetCase->print_status_id])->pluck('title')[0] : 'Не задан',
                    ($cartSetCase->date_send) ? $cartSetCase->date_send : 'Не задана',
                    ($cartSetCase->supposed_date) ? $cartSetCase->supposed_date : 'Не задана',
                    ($cartSetCase->date_back) ? $cartSetCase->date_back : 'Не задана',
                ];
                $i++;
            }

        }

        $callback = function() use ($result)
        {
            //$FH = fopen('php://output', 'w');
            $str = '';
            foreach ($result as $i => $row) {
                foreach ($row as $r)
                    $str .= $r . ";";
                $str .= "\r\n";
                //fputcsv($FH, $row, ';');
            }
            $str = iconv("UTF-8", "WINDOWS-1251//IGNORE",  $str);
            file_put_contents('php://output', $str);
            //fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function csvQuery($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);
        $orders = Order::whereBetween('order_ts', [$startDate, $endDate])->get();
        $result = [];
        /**/
        $result[0] = [
            'ID заказа',
            'Дата заказа',
            'Статус заказа',
            'Название футболки',
            'Код футболки',
            'Background ID',
            'Background name',
            'Цена',
        ];
        $i = 1;
        foreach ($orders as $order)
        {
            foreach($order->cart->cartSetProducts as $cartSetProduct)
            {
                $product = $cartSetProduct->offer->product;
                if ($product->option_group_id == 9)
                {
                    $result[$i] = [
                        $order->order_id,
                        $order->order_ts,
                        OrderStatus::where(['status_id' => $order->order_status_id])->pluck('status_name')[0],
                        $product->name,
                        $product->code,
                        $product->background_id,
                        ($product->background_id) ? Background::where(['id' => $product['background_id']])->pluck('name')[0] : 'Не задан',
                        $product->price,
                    ];
                }
            }

            $i++;
        }

        $FH = fopen('file.csv', 'w');
        foreach ($result as $row) {
            fputcsv($FH, $row);
        }
        fclose($FH);

        return 'Complete';
    }
}
