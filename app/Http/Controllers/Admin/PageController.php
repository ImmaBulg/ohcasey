<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Shop\Page;

class PageController extends Controller
{
    protected $perPage = 15;

    protected $rules = [
        'title' => 'required',
        'slug' => 'required|unique:pages,slug',
        //'content' => 'required'
    ];

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {   
        $query = Page::query();

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            $fields = Page::$filteredByText;
            foreach ($fields as $index => $field) {
                $method = $index ? "orWhere" : "where";
                $query->{$method}($field,'iLIKE',"%{$search}%");
            }
        }

        $options = $query->orderBy('slug')->paginate($this->perPage);

        if ($options->currentPage() > $options->lastPage()) {
            $options = $query->paginate($this->perPage, ['*'], 'page', $options->lastPage());
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
        $page = new Page;
        $this->validate($request, $rules);
        $page->fill($request->all());
        $page->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $page = Page::findOrFail($id)->toArray();
        return response()->json(['form' => $page]);
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
        $rules['slug'] = 'required|unique:pages,slug,'.$id;
        $this->validate($request, $rules);

        $page = Page::findOrFail($id);
        $page->fill($request->all())->save();
    }

}
