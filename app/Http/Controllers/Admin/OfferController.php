<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Shop\Offer;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Product;
use App\Models\Shop\OptionGroup;

class OfferController extends Controller
{
    protected $rules = [
        'product_id' => 'required',
        'options' => 'required|array',
        'active' => 'boolean'
    ];

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        if(!$request->has('product_id')) abort(400);
        $product = Product::findOrFail($request->product_id);
        $options = OptionGroup::find($product->option_group_id)->options;
        $values = [];
        foreach ($options as $o) {
            $values[(string)$o->id] = OptionValue::where('option_id', $o->id)->get();
        }
        return response()->json([
            'data' => [
                    'values' => $values,
                    'product' => $product
                ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $offer = Offer::findOrFail($id);
        $offer_values = $offer->optionValues;
        $values = [];
        foreach ($offer_values as $ov) {
            $values[(string)$ov->option_id] = OptionValue::where('option_id', $ov->option_id)->get();
        }
        $product = $offer->product;
        return response()->json([
            'form' => $offer,
            'data' => [
                    'offer_values' => $offer_values,
                    'values' => $values,
                    'product' => $product
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

        $offer = Offer::findOrFail($id);
        $offer->fill($request->all())->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $product = Product::findOrFail($request->product_id);
        $options = OptionGroup::find($product->option_group_id)->options;
        $rules = $this->rules;
        $rules['options'] = 'required|array|size:'.count($options);
        $this->validate($request, $rules);
        $offer = new Offer;
        $offer->fill($request->except('options'));
        $offer->save();
        $offer->optionValues()->sync($request->options);
    }

}
