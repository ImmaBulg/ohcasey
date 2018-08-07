<?php

namespace App\Http\Controllers;

use App\Models\Background;
use App\Models\Casey;
use App\Models\Device;
use App\Models\Item;
use App\Models\Shop\Offer;
use App\Models\Shop\Option;
use App\Models\Shop\OptionValue;
use App\Models\Shop\OptionGroup;
use App\Models\Shop\Product;
use App\Models\Shop\Category;
use App\Models\Shop\Page;
use App\Ohcasey\Ohcasey;
use App\Support\ProductImageMaker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $params = $request->only(['category_id', 'device', 'color', 'case', 'sort']);

        $category = Category::findOrFail($params['category_id']);
        $children = $category->selfChildren->pluck('id')->push($category->id);
        /*$products = Product::query()
            ->whereHas('categories', function ($q) use ($children) {
                $q->whereIn('id', $children);
            })
            ->filter($params)
            ->paginate(30)->appends($params);
        return $products;*/
		$data = Product::query()
            ->whereHas('categories', function ($q) use ($children) {
                $q->whereIn('id', $children);
            })
            ->filter($params)
			->get();
		$from = 1;
		$to = $data->count();
		$last_page = 1;
		$next_page_url = null;
		$per_page = $data->count();
		$prev_page_url = null;
		$total = $data->count();
			
        return compact('data', 'from', 'to', 'last_page', 'next_page_url', 'per_page', 'prev_page_url', 'total');
    }

    /**
     * Show product
     * @param  integer $id Product id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Request $request)
    {
        /** @var Product $product */
        try {
            $product = Product::query()
                ->with('background')
                ->active()
                ->hasOffer()
                ->find($id);
        } catch (\Exception $e) {
            return redirect('/' . $id, 301);
        }
        if ($product == null)
            return redirect('/catalog', 301);
        ++$product->view_count;
        $product->save();
        //dump($product);
        if ($request->all() != [] && $product->option_group_id != 1 && $product->option_group_id != 2)
            return redirect()->route('shop.product.show', $id);
        if ($request->all() == [] && ($product->option_group_id == 1 || $product->option_group_id == 2))
            return redirect()->route('shop.product.show', [$id, 'device' => 'iphonex', 'sort' => '', 'color' => '1', 'case' => 'silicone']);
        else
            switch ($product->option_group_id) {
                case OptionGroup::ID_CASE_GROUP:
                    return $this->showCase($product);
                default:
                    return $this->showDefault($product);
            }
    }

    /**
     * Подходит для вывода товаров для которых существует всего одна вариаций опции.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDefault(Product $product)
    {
        $product->load([
            'offers' => function ($query) {
                return $query->where('active', true)->with('optionValues');
            },
            'categories',
            'optionGroup',
            'photos'
        ]);

        if(!$category = $product->firstCategory()){
            return redirect('/collections', 301);
        }
        $categoryParent = $category->selfParent;

        $breadcrumbs = [];

        if ($categoryParent) {
            array_push($breadcrumbs, ['href' => $categoryParent->url, 'name' => $categoryParent->name]);
        }
        array_push($breadcrumbs, ['href' => $category->url, 'name' => $category->name]);
        array_push($breadcrumbs, ['name' => $product->name]);

        return view('site.shop.product.default.show', compact('product', 'breadcrumbs'));
    }

    public function showCase(Product $product)
    {
        /** @var Device[]|Collection $devices_colors */
        $devices_colors = Device::select('device_name', 'device_colors')->get();

        $devices = $devices_colors->pluck('device_name');
        //$devices = $devices->reverse();
        $colors = $devices->combine(
            $devices_colors->pluck('device_colors')
        );

        /** @var Casey $cases */
        $cases = Casey::select('case_name')->get()->pluck('case_name');

        // $options = $this->options($product->id);
        $offers = Offer::where('product_id', $product->id)->with(['optionValues' => function ($q) {
            $q->select('id', 'option_id', 'value', 'title', 'description', 'image', 'order');
        }])->get(['id']);
        $options = $product->optionGroup->options()->select(['id', 'key', 'name', 'order'])->get();

        /** @var Item $item */
		// я хз для чего был этот код. закоментировал
        $item = Item::find(Ohcasey::SKU_DEVICE);
        $product->price = $item->item_cost;

        if(!$category = $product->firstCategory()){
            return redirect('/catalog', 301);
        }
        $categoryParent = $category->selfParent;

        $breadcrumbs = [];

        if ($categoryParent) {
            array_push($breadcrumbs, ['href' => $categoryParent->url, 'name' => $categoryParent->name]);
        }
        array_push($breadcrumbs, ['href' => $category->url, 'name' => $category->name]);
        array_push($breadcrumbs, ['name' => $product->name]);

        $device_name = request()->get('device', 'iphone7');
        $device_caption = OptionValue::where('value', $device_name)->value('title');

        $case_name = request()->get('case', 'silicone');
        $case_caption = OptionValue::where('value', $case_name)->value('title');

        return view('site.shop.product.case.show',
            compact('product', 'breadcrumbs', 'offers', 'options', 'devices', 'colors', 'cases', 'device_caption', 'case_caption'));
    }

    // /**
    //  * Get options for product by id
    //  * @param  integer $id Product id
    //  * @return array
    //  */
    // public function getOptions($productId)
    // {
    //     $response = [];
    //     $offers = Offer::where('product_id', $productId)->with('optionValues')->get();
    //     $options = Product::whereId($productId)->first()->optionGroup->options;

    //     dd($offers, $options);
    //     $related = OptionGroup::whereHas('offer', function($q) use ($id) {
    //             $q->where('product_id', $id);
    //         })->with('offer')
    //         ->get()->toArray();
    //     $response = $related;
    //     $i = 0;
    //     foreach ($related as $r) {
    //         $options = Option::with(['values' => function($q) use ($r) {
    //             return $q->whereIn('id', $r['value']);
    //         }])->get()->toArray();
    //         $response[$i]['options'] = $options;
    //         $i ++;
    //     }
    //     return $response;
    // }

    /**
     * Получить картинку - наклейка + девайс + чехол + цвет
     * @param Request $request
     * @param ProductImageMaker $maker
     */
    public function image(Request $request, ProductImageMaker $maker)
    {
        $bgName = trim($request->get('bgName'));
        $deviceName = $request->get('deviceName');
        $caseFileName = trim($request->get('caseFileName'));
        $deviceColorIndex = $request->get('deviceColorIndex');
        if (strpos($caseFileName, 'glitter') !== false){
                $deviceColorIndex = '_0';
        }//in glitter, color is not taken into account

        $cacheKey = 'device_image_' . $bgName . $deviceName . $caseFileName . $deviceColorIndex;
        $imageBinary = \Cache::remember($cacheKey, 24*60 ,function () use ($maker, $bgName, $deviceName, $caseFileName, $deviceColorIndex) {
            Background::where('name', $bgName)->firstOrFail();
            if (!in_array($caseFileName, Casey::pluck('case_name')->toArray())) {
                throw new \Exception('Invalid case ' . $caseFileName);
            }
            if (!is_numeric($deviceColorIndex)) {
                $deviceColor = ProductImageMaker::ONLY_ONE_COLOR;
            } else {
                /** @var Device $device */
                if ($deviceName === 'iphone') $deviceName = 'iphonex';
                if ($deviceName === 'samsung') $deviceName = 'sgs7e';
                $device = Device::where('device_name', $deviceName)->firstOrFail();
                $deviceColor = $device->device_colors[$deviceColorIndex];
            }
            $image = $maker->make($bgName, $deviceName, $caseFileName, $deviceColorIndex, $deviceColor);
            return $image->__toString();
        });

        header('Content-Type: image/png');
        header('Cache-Control: max-age=31556926');

        echo $imageBinary;
    }

    // /**
    //  * @param $productId
    //  * @return array
    //  */
    // public function options($productId)
    // {
    //     // @TODO - отрефакторить вместе с фронтом
    //     /** @var Offer[]|Collection[] $response */
    //     $offers = Offer::where('product_id', $productId)->get();

    //     /** @var Product $product */
    //     $product = Product::findOrFail($productId);

    //     $response = [];

    //     $offers->each(function (Offer $offer) use (&$response, $product) {
    //         $obj = $offer->toArray();
    //         $obj['offer'] = $offer->toArray();
    //         $obj['option_values'] = $offer->option_values->toArray();
    //         $obj['value'] = $offer->options;
    //         $obj['options'] = Option::query()
    //             ->whereHas('groups', function ($query) use ($product) {
    //                 return $query->where('option_group_id', $product->option_group_id);
    //             })
    //             ->with([
    //                 'values' => function ($q) use ($offer) {
    //                     return $q->whereIn('id', $offer->options)
    //                         ->orderBy('order');
    //                 },
    //             ])
    //             ->orderBy('order')
    //             ->get()
    //             ->toArray();

    //         $response[] = $obj;
    //     });

    //     return $response;
    // }

   /*  public function sitemap()
    {
        $links = [];
        $count = 0;

        $devices = Device::select('device_name', 'device_cases')->get()->toArray();

        Product::query()
            ->where('option_group_id', OptionGroup::ID_CASE_GROUP)
            ->with('background')
            ->active()
            ->hasOffer()
            ->get()
            ->each(function ($product) use (&$links, $devices, &$count) {
                foreach ($devices as $device) {
                    foreach ($device['device_cases'] as $device_case) {
                        $links[] = route('shop.product.show', [
                            'id' => $product->id,
                            'device' => $device['device_name'],
                            'case' => $device_case,
                        ]);
                        $count++;
                    }
                }
            });

        //echo $count;

        return response()
            ->view('site.shop.sitemap', compact('links'), 200)
            ->header('Content-Type', 'text/xm');
    }  */

	public function sitemap()
    {
        $links = [];
        $count = 0;

		// статические страницы
		Page::query()
			->each(function ($pages) use (&$links, &$count) {
				// если главная страница
				if($pages->slug == "/"){
					$links[] = array(
						"loc" => getenv('APP_URL'),
						"changefreq" => "daily",
						"priority" => "1"
					);
				}else{
					$links[] = array(
						"loc" => getenv('APP_URL') . $pages->slug . "/",
						"changefreq" => "weekly",
						"priority" => "0.7"
					);
				}
				$count++;
			});
			
		// генератор чехлов
		$links[] = array(
			"loc" => getenv('APP_URL') . "custom",
			"changefreq" => "daily",
			"priority" => "1"
		);
		
		
		// активные категории
		Category::query()
			->where('active', true)
			->each(function ($categories) use (&$links, &$count) {
				$categoryParent = $categories->parent;
				if(!empty($categoryParent)){
					$category = Category::whereId($categories->parent)->firstOrFail();
					$urlParent = $category->slug . "/";
				}else{
					$urlParent = "";
				}
				$loc = getenv('APP_URL') . $urlParent . $categories->slug;
				$links[] = array(
					"loc" => $loc,
					"changefreq" => "daily",
					"priority" => "1"
				);
				$count++;
			});

		// активные продукты 
        $devices = Device::select('device_name', 'device_cases')->get()->toArray();
        Product::query()
            // ->where('option_group_id', OptionGroup::ID_CASE_GROUP)
			->where('active', true)
            ->with('background')
            ->active()
            ->hasOffer()
            ->get()
            ->each(function ($product) use (&$links, $devices, &$count) {
                foreach ($devices as $device) {
                    foreach ($device['device_cases'] as $device_case) {
                        $links[] = array(
							"loc" => route('shop.product.show', [
								'id' => $product->id,
								'device' => $device['device_name'],
								'case' => $device_case,
							]),
							"changefreq" => "daily",
							"priority" => "0.9"
						);
                        $count++;
                    }
                }
            });
			
        // echo $count;//exit;

        return response()
            ->view('site.shop.sitemap', compact('links'), 200)
            ->header('Content-Type', 'text/xm');
    }
	
}
