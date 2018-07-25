<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\Option;

class OptionController extends Controller
{
    protected $perPage = 15;

    protected $rules = [
        'name' => 'required',
        'key' => 'required|unique:options,key',
        'order' => 'required|numeric'
    ];

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {   
        $per_page = $request->get('per_page', $this->perPage);

        $query = Option::query();

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            $fields = Option::$filteredByText;
            foreach ($fields as $index => $field) {
                $method = $index ? "orWhere" : "where";
                $query->{$method}($field,'iLIKE',"%{$search}%");
            }
        }

        $options = $query->orderBy('order')->paginate($per_page);

        if ($options->currentPage() > $options->lastPage()) {
            $options = $query->paginate($per_page, ['*'], 'page', $options->lastPage());
        }

        return $options;
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
        $option = new Option;
        $this->validate($request, $rules);
        $option->fill($request->all());
        $option->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $option = Option::findOrFail($id)->toArray();
        return response()->json(['form' => $option]);
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
        $rules['key'] = 'required|unique:options,key,'.$id;
        $this->validate($request, $rules);

        $option = Option::findOrFail($id);
        $option->fill($request->all())->save();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionsList()
    {
        return response()->json(Option::all());
    }
}
