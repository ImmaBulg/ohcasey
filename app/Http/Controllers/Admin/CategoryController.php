<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatMeta;
use App\Models\Shop\OptionValue;
use Illuminate\Http\Request;
use App\Models\Shop\Category;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    /**
     * Validation rules
     * @var array
     */
    protected $rules = [
        'name' => 'required',
        'title' => 'required',
        'slug' => 'required|alpha_dash|unique:categories,slug|max:255',
        'image' => 'image|mimes:jpeg,gif,png|max:10240',
        'order' => 'integer',
        'published_at' => 'date',
    ];

    /**
     * Categories list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	$categories = Category::orderBy('order')->get();
    	return view('admin.category.index')->with(compact('categories'));
    }

    /**
     * Create Category form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $models = OptionValue::where(['option_id' => 1])->orderBy('order', 'asc')->get();
    	return view('admin.category.create', ['models' => $models]);
    }

    /**
     * Edit Category form
     * @param  integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
    	$category = Category::findOrFail($id);
        $models = OptionValue::where(['option_id' => 1])->orderBy('order', 'asc')->get();
        $tmp = CatMeta::where([
            'cat_id' => $id
        ])->get();
        $tags = [];
        foreach ($tmp as $t)
            $tags[$t->phone] = $t;
        return view('admin.category.edit')->with(compact('category', 'models', 'tags'));
    }

    /**
     * Create new Category
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {   
        $this->validate($request, $this->rules);
        $category = new Category;
        $data = $request->only($category->getFillable());
        $category->fill($data)->save();
        if($file = $request->file('image')){
            $image = $this->saveImage($file, 800, 800);
            $category->image = $image;
            $category->save();
        }
        if($file = $request->file('banner_image')){
            $image = $this->saveImage($file);
            $category->banner_image = $image;
            $category->save();
        }
        
        return redirect()->route('admin.category.index');
    }

    /**
     * Update exists Category
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {   
        $category = Category::findOrFail($id);

        $rules = $this->rules;
        //Для версии laravel 5.3 использовать другое решение:
        $rules['slug'] = 'required|alpha_dash|unique:categories,slug,'.$id.'|max:255';
        $this->validate($request, $rules);

        $data = $request->only($category->getFillable());
        $category->update($data);

        if($file = $request->file('image')){
            $image = $this->saveImage($file, 800, 800);
            $category->image = $image;
            $category->save();
        }
        if($file = $request->file('banner_image')){
            $image = $this->saveImage($file);
            $category->banner_image = $image;
            $category->save();
        }
        return redirect()->route('admin.category.index');
    }

    /**
     * Delete Category
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.category.index');
    }

    /**
     * Crop, resize and save image
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string $name
     */
    private function saveImage($file, $width = null, $height = null)
    {
        if(!file_exists(config('category.image_path'))){
            mkdir(config('category.image_path'), 0775, true);
        }
        $manager = new ImageManager(array('driver' => 'imagick'));
        $img = $manager->make($file->getRealPath());
        $name = md5($file).'.'.$file->getClientOriginalExtension();
        if($width and $height){
            $img->fit($width, $height)->save(public_path(config('category.image_path')).$name);
        }else{
            $img->save(public_path(config('category.image_path')).$name);
        }
        return $name;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoriesList()
    {
        return response()->json(Category::pathList()->get());
    }

    public function updateMeta(Request $request)
    {
        $inputs = $request->all();
        $cat_meta = CatMeta::where([
            'cat_id' => $inputs['cat'],
            'phone' => $inputs['model'],
        ])->first();
        //dump($request);
        \DB::transaction(function() use (&$cat_meta, $inputs) {
            if ($cat_meta) {
                $cat_meta->h1 = $inputs['meta_h1'];
                $cat_meta->title = $inputs['meta_title'];
                $cat_meta->desc = $inputs['meta_desc'];
                $cat_meta->keywords = $inputs['meta_keywords'];
                $cat_meta->text_up = $inputs['text_up'];
                $cat_meta->text_down = $inputs['text_down'];
                $cat_meta->save();
            }
            else {
                $cat_meta = new CatMeta();
                $cat_meta->cat_id = $inputs['cat'];
                $cat_meta->phone = $inputs['model'];
                $cat_meta->h1 = $inputs['meta_h1'];
                $cat_meta->title = $inputs['meta_title'];
                $cat_meta->desc = $inputs['meta_desc'];
                $cat_meta->keywords = $inputs['meta_keywords'];
                $cat_meta->text_up = $inputs['text_up'];
                $cat_meta->text_down = $inputs['text_down'];
                $cat_meta->save();
            }
        });

        return ['answer' => 'success'];
    }

}