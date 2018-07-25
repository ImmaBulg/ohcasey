<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\Tag;

class TagController extends Controller
{
    protected $perPage = 15;
    
    protected $rules = [
        'name' => 'required',
    ];

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', $this->perPage);

        $query = Tag::query();

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            $fields = Tag::$filteredByText;
            foreach ($fields as $index => $field) {
                $method = $index ? "orWhere" : "where";
                $query->{$method}($field,'iLIKE',"%{$search}%");
            }
        }

        $tags = $query->orderBy('order')->paginate($per_page);

        return $tags;
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
        $tag = new Tag;
        $this->validate($request, $rules);
        $tag->fill($request->all());
        $tag->save();
        return $tag;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $tag = Tag::findOrFail($id)->toArray();
        return response()->json(['form' => $tag]);
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

        $tag = Tag::findOrFail($id);
        $tag->fill($request->all())->save();
        return $tag;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag  ::findOrFail($id);
        $tag->delete();
    }
}