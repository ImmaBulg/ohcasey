<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.09.2018
 * Time: 10:24
 */

namespace App\Http\Controllers;


use App\Models\Shop\Category;
use App\Models\Shop\Product;

class SetCategories extends Controller
{
    public function setCategories() {
        $categories = Category::where(['parent' => 18])->get();
        $products = Product::where(['option_group_id' => 1])->get();
        foreach ($products as $product) {
            $tmp = clone $categories;
            $megred = $tmp->merge($product->categories()->get());
            $product->setCategoriesAttribute($megred);
        }
        dump($categories);
    }
}