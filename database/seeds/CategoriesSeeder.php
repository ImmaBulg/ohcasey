<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('categories')->count()){
            DB::table('categories')->insert([
                [
                    'name' => 'Аксессуары',
                    'title' => 'Аксессуары для телефонов',
                    'slug' => 'accessories',
                    'parent' => null
                ],
                [
                    'name' => 'Наушники',
                    'title' => 'Наушники для телефонов',
                    'slug' => 'headphones',
                    'parent' => null
                ],
                [
                    'name' => 'Зарядные устройства',
                    'title' => 'Зарядные устройства для телефонов',
                    'slug' => 'charging',
                    'parent' => 1
                ],
                [
                    'name' => 'Для iPhone',
                    'title' => 'Зарядные устройства для iPhone',
                    'slug' => 'for-iphone',
                    'parent' => 3
                ]
            ]);
        }
    }
}
