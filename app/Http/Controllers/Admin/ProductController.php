<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop\OptionGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Shop\Product;
use App\Models\Device;
use App\Models\Shop\Option;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Category;
use App\Models\Background;

class ProductController extends Controller
{
    protected $perPage = 15;

    protected $rules = [
        'name'            => 'required',
        'option_group_id' => 'required',
        'title'           => 'required|max:255',
        'code'            => 'required|unique:products,code',
        'description'     => 'required|max:10000',
        'price'           => 'required|numeric',
        'discount'        => 'numeric',
        'active'          => 'boolean',
        'bestseller'      => 'boolean'
    ];

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {   
        $perPage = $request->get('per_page', $this->perPage);

        /** @var  $query */
        $query = Product::query();

        if ($request->has('search')) {
            $search = trim($request->get('search'));

            $fields = Product::$filteredByText;

            foreach ($fields as $index => $field) {
                $method = $index ? "orWhere" : "where";
                $query->{$method}($field,'iLIKE',"%{$search}%");
            }
        }

        if ($request->has('category')) {
            $category_id = $request->get('category');

            $query->whereHas('categories', function($q) use ($category_id) {
                $q->where('id', $category_id);
            });
        }

        if ($request->has('active')) {
            $query->whereActive((bool) $request->get('active'));
        }

        $products = $query->with('categories')->with('photos')->orderBy('order')->paginate($perPage);

        if ($products->currentPage() > $products->lastPage()) {
            $products = $query->paginate($perPage, ['*'], 'page', $products->lastPage());
        }

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_options = $this->categoryOptions();

        $background_options = Background::select('id', 'name')->orderby('name')->get();

        $optionGroups = OptionGroup::select('id', 'name')->orderby('name')->get();

        $draft = Product::draft()->first();

        if (empty($draft)) {
            $draft = new Product;
            $draft->draft_user_id = \Auth::id();
            $draft->price = 0;
            $draft->active = true;
            $draft->code = null;
            $draft->background_id = null;
            $draft->related = [];
            $draft->tags = [];
            $draft->save();
        }

        return response()->json([
            'form' => $draft,
            'data' => [
                'id'                 => $draft->id,
                'category_options'   => $category_options,
                'background_options' => $background_options,
                'option_groups'      => $optionGroups,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = $this->rules;
        $draft = Product::draft()->first();

        if (!empty($draft)) {
            $rules['code'] = 'required|unique:products,code,'.$draft->id;
            $product = $draft;
        } else {
            $product = new Product;
        }

        $this->validate($request, $rules);

        $product->fill($request->all());

        if (!$request->has('draft')) {
            $product->draft_user_id = null;
        }

        $product->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $product = Product::with([
                'categories' => function($query){
                    $query->select('id');
                },
                'optionGroup',
				'related',
				'tags'
            ])
            ->findOrFail($id)
            ->toArray();

        $category_options = $this->categoryOptions();

        $background_options = Background::select('id', 'name')->orderby('name')->get();

        return response()->json([
            'form' => $product,
            'data' => [
                'id'                 => $product['id'],
                'category_options'   => $category_options,
                'background_options' => $background_options
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $rules = $this->rules;

        //@TODO Для версии laravel 5.3 использовать другое решение:
        $rules['code'] = 'required|unique:products,code,'.$id;
        $this->validate($request, $rules);

        $product = Product::findOrFail($id);
        $data = $request->only($product->getFillable());
        $data['draft_user_id'] = null;
        $product->fill($data)->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }

    /**
     * Categories list for Product form
     * @return Collection
     */
    private function categoryOptions()
    {
        return Category::pathList()->get();
    }

    public function generateCaseOffers($id)
    {
        $product = Product::findOrFail($id);
        $devices = Device::all();
        $options = Product::findOrFail($id)->optionGroup()->first()->options()->get()->pluck('key')->toArray();
        $option_ids = [];
        foreach ($options as $o) {
            $option_ids[$o] = Option::whereKey($o)->first()->id;
        }

        $offers = $values = $counts = [];
        $counts['offers_before'] = $product->offers()->count();
        $counts['devices'] = $devices->count();
        $counts['colors'] = $counts['cases'] = 0;

        //loop for devices values
        foreach ($devices as $device) {
            $values['device'] = $device->device_name;
            $casePath = storage_path('app/device/'.$device->device_name.'/case');
            $casesFiles = array_diff(scandir($casePath), array('..', '.'));
            $counts['cases'] += count($casesFiles);
            //loop for cases values
            foreach ($casesFiles as $caseFile) {
                $case = basename($caseFile, '.png');
                $values['case'] = $case;
                $counts['colors'] += count($device->device_colors);
                //loop for colors values
                foreach ($device->device_colors as $color) {
                    $values['color'] = $color;
                    $offer_ids = [];
                    foreach ($option_ids as $k => $o) {
						$offer_one = OptionValue::where('option_id', $option_ids[$k])->where('value', $values[$k])->orderBy('id', 'desc')->first();
						if(!empty($offer_one)) $offer_ids[] = $offer_one->id;
                    }
                    $offers[] = $offer_ids;
                    $offerQuery = $product->offers();
                    $i = 0;
                    foreach ($option_ids as $k => $o) {
						if(isset($offer_ids[$i])){
							$offerQuery->whereHas('optionValues',  function($q) use ($offer_ids, $o, $i) {
								$q->where([
									['id', '=', $offer_ids[$i]],
									['option_id', '=', $o],
								]);
							});
							$i++;
						}
                    }
                    $offer = $offerQuery->get();
                    if($offer->count() == 0) {
                        //add new offer for product
                        $newOffer = $product->offers()->create(['product_id' => $product->id]);
                        $newOffer->optionValues()->sync($offer_ids);
                    }
                }
            }
        }
        $counts['offers_affter'] = $product->offers()->count();
        return response()->json(['counts' => $counts]);
        // dd($product->offers()->count(), $counts, $offers);
    }
}