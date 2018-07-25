<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Ohcasey\ProductPhotoManager;
use Illuminate\Http\Request;
use App\Models\Shop\Product;
use App\Models\Shop\Photo;

class PhotoController extends Controller
{
    /**
     * Validation rules
     * @var array
     */
    protected $rules = [
        'photo' => 'image|required|mimes:jpeg,gif,png|max:10240',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->has('id')) {
            return abort(400);
        }

        $type = $request->get('photoable_type', Photo::DEFAULT_TYPE);

        return Photo::select(['id', 'name'])
            ->where('photoable_type', $type)
            ->where('photoable_id', $request->get('id'))
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param ProductPhotoManager $photoManager
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductPhotoManager $photoManager)
    {
        if ($request->has('id')) {
            $product = Product::findOrFail($request->get('id'));
        } else {
            $product = Product::draft()->first();
            if(empty($product)) {
                return abort(400);
            }
        }

        $rules = $this->rules;
        $this->validate($request, $rules);

        if ($file = $request->file('photo')){
            $image = $photoManager->savePhoto($file);
            $photo = new Photo(['name' => $image]);
            $product->photos()->save($photo);
            return response()->json(['status'=> 'success', 'message' => 'Add new photo file for product', 'data' => ['id' => $photo->id]]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if (count($request->all()) == 0){
            Photo::findOrFail($id)->touch();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Photo $photo */
        $photo = Photo::findOrFail($id);
        $photo->delete();
        \File::delete(public_path(config('product.photo.path')) . $photo->name);
    }
}
