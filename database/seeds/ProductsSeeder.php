<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('products')->count()){
            $products = factory(App\Models\Shop\Product::class, 100)->create();
            $products->each(function($product){
                $count = mt_rand(1,4);
                $cats = App\Models\Shop\Category::all()->random($count)->pluck('id')->toArray();
                $product->categories()->sync($cats);
            });
        }
    }
}
