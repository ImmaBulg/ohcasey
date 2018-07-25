<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Option;
use Illuminate\Pagination\LengthAwarePaginator;

class OptionValueController extends Controller
{
    protected $per_page = 15;

    protected $rules = [
        'value' => 'required',
        'title' => 'required',
        'order' => 'numeric',
        'option_id' => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {   
        $per_page = $this->per_page;
        if($request->has('per_page')){
            $per_page = $request->per_page;
        }

        $query = OptionValue::select();

        if($request->has('search')) {
            $search = trim($request->get('search'));
            $fields = OptionValue::$filteredByText;
            foreach ($fields as $index => $field) {
                $method = $index ? "orWhere" : "where";
                $query->{$method}($field,'iLIKE',"%{$search}%");
            }
        }

        if($request->has('category')){
            $category_id = $request->get('category');
            $query->whereHas('option', function($q) use ($category_id) {
                $q->where('id', $category_id);
            });
        }

        $option_values = $query->orderBy('order')->paginate($per_page);

        if($option_values->currentPage() > $option_values->lastPage()) {
            $option_values = $query->paginate($per_page, ['*'], 'page', $option_values->lastPage());
        }

        return $option_values;
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
        $option_value = new OptionValue;
        $this->validate($request, $rules);
        $data = $request->only($option_value->getFillable());
        $option_value->fill($data);
        $option_value->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $option_values = OptionValue::findOrFail($id)->toArray();
        $options = Option::select('id', 'name')->orderby('order')->get();
        return response()->json([
            'form' => $option_values,
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
        //@TODO Для версии laravel 5.3 использовать другое решение:
        // $rules['key'] = 'required|unique:options,key,'.$id;
        $this->validate($request, $rules);

        $option_value = OptionValue::findOrFail($id);
        $data = $request->only($option_value->getFillable());
        $option_value->fill($data)->save();
    }

}
