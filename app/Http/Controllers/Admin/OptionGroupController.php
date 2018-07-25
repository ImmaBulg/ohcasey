<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop\Offer;
use App\Models\Shop\Product;
use App\Models\Shop\OptionGroup;
use App\Models\Shop\Option;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class OptionGroupController extends Controller
{
    protected $perPage = 100;

    protected $rules = [
        'name' => 'required',
        'options' => 'required|array',
    ];

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $query = OptionGroup::query();

        return $query->paginate($this->perPage);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {   
        $optionGroup = OptionGroup::find($id);
        $offers = $optionGroup->offers()->with(['optionValues' => function($q){
            return $q->select('title');
        }])->paginate($this->perPage);

        return response()->json([
            'rows' => $offers,
            'page' => $optionGroup,
        ]);
    }

    /**
     * Show the form for creating the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        return response()->json([
            'data' => [
                    'options' => Option::orderBy('order')->get(),
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
        $option_group = new OptionGroup;
        $this->validate($request, $rules);
        $data = $request->only($option_group->getFillable());
        $option_group->fill($data);
        $option_group->save();
        $option_group->options()->attach($request->options);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $option_group = OptionGroup::findOrFail($id);
        $data = $option_group->toArray();
        $data['options'] = $option_group->options->pluck('id')->toArray();
        $options = Option::select('id', 'name')->orderby('order')->get();
        return response()->json([
            'form' => $data,
            'data' => [
                'options' => $options
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
        $this->validate($request, $rules);

        $option_group = OptionGroup::findOrFail($id);
        $data = $request->only($option_group->getFillable());
        $option_group->fill($data)->save();
        $option_group->options()->sync($request->options);
    }

    // /**
    //  * @param int $id
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function show($id)
    // {
    //     $optionGroup = OptionGroup::findOrFail($id);

    //     $productTable = with(new Product())->getTable();
    //     $offerTable = with(new Offer())->getTable();

    //     $offers = Offer::query()
    //         ->join($productTable, $productTable . '.id', '=', $offerTable . '.product_id')
    //         ->where($productTable . '.option_group_id', $id);

    //     return response()->json([
    //         'rows' => $offers
    //             ->orderBy($offerTable . '.id', 'desc')
    //             ->paginate($this->perPage),
    //         'page' => $optionGroup,
    //     ]);
    // }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductOffers($id)
    {   
        return Offer::whereProductId($id)->with(['optionValues' => function($q){
            return $q->select('title');
        }])->paginate($this->perPage);
    }

    // /**
    //  * Поиск значений опций.
    //  *
    //  * @param Request $request
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function searchValues(Request $request)
    // {
    //     /** @var Builder|\Illuminate\Database\Eloquent\Builder $query */
    //     $query = Offer::query();

    //     $perPage = $request->get('perPage', $this->perPage);

    //     if ($request->get('product_id')) {
    //         $productId = $request->get('product_id');
    //         $offerTable = with(new Offer())->getTable();
    //         $productTable = with(new Product())->getTable();

    //         $query->join($productTable, function (JoinClause $join) use ($productTable, $query, $offerTable, $productId) {
    //                 $join->on($productTable . '.id', '=', $offerTable . '.product_id')
    //                     ->where($join->table . '.id', '=', $productId);
    //             });
    //     }

    //     return response()->json([
    //         'rows' => $query
    //             ->orderBy($query->getModel()->getTable() . '.id', 'desc')
    //             ->paginate($perPage),
    //         'page' => '',
    //     ]);
    // }
}
