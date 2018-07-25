<?php

use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('options')->count()) {
            DB::table('options')->insert([
                [
                    'key' => 'device',
                    'name' => 'Модель телефона',
                    'order' => 100,
                    'created_at' => new \DateTime()
                ],
                [
                    'key' => 'color',
                    'name' => 'Цвет телефона',
                    'order' => 200,
                    'created_at' => new \DateTime()
                ],
                [
                    'key' => 'case',
                    'name' => 'Материал чехла',
                    'order' => 300,
                    'created_at' => new \DateTime()
                ]
            ]);
        }

        if (!DB::table('option_values')->count()) {
            DB::table('option_values')->insert([
                //device
                [
                    'option_id' => 1,
                    'value' => 'iphone6s',
                    'title' => 'iPhone 6s',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 1,
                    'value' => 'iphone7',
                    'title' => 'iPhone 7',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 1,
                    'value' => 'iphone5',
                    'title' => 'iPhone 5',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                //color
                [
                    'option_id' => 2,
                    'value' => '#cf9657',
                    'title' => 'Красный закат',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 2,
                    'value' => '#e2a2ac',
                    'title' => 'Пыльная роза',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 2,
                    'value' => '#c4c7ce',
                    'title' => 'Морской песок',
                    'description' => null,
                    'created_at' => new \DateTime()
                ],
                //case
                [
                    'option_id' => 3,
                    'value' => 'plastic',
                    'title' => 'Матовый пластик',
                    'description' => 'Тонкий, но прочный. Бархатистый на ощупь. Не скользит в руке',
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 3,
                    'value' => 'silicone',
                    'title' => 'Силикон',
                    'description' => 'Оптимальная толщина. Отличная защита устройства при падениях. Не скользит в руке',
                    'created_at' => new \DateTime()
                ],
                [
                    'option_id' => 3,
                    'value' => 'softtouch',
                    'title' => 'Soft Touch',
                    'description' => 'Тонкий, но прочный. Бархатистый на ощупь. Не скользит в руке',
                    'created_at' => new \DateTime()
                ],
            ]);
        }

        if (!DB::table('option_groups')->count()) {
            DB::table('option_groups')->insert([
                'name' => 'Чехлы',
            ]);
        }

        if (!DB::table('option_option_group')->count()) {
            DB::table('option_option_group')->insert([
                [
                    'option_id' => 1,
                    'option_group_id' => 1,
                ],
                [
                    'option_id' => 2,
                    'option_group_id' => 1,
                ],
                [
                    'option_id' => 3,
                    'option_group_id' => 1,
                ],
            ]);
        }

        if (!DB::table('offers')->count()) {
            $product = \DB::table('products')->orderBy('created_at', 'desc')->first();
            if (!empty($product)) {
                DB::table('offers')->insert([
                    [   
                        'product_id' => $product->id,
                        'options'    => json_encode([1,4,9]),
                        'created_at' => new \DateTime()
                    ],
                    [   
                        'product_id' => $product->id,
                        'options'    => json_encode([2,4,9]),
                        'created_at' => new \DateTime()
                    ],
                    [   
                        'product_id' => $product->id,
                        'options'    => json_encode([1,5,7]),
                        'created_at' => new \DateTime()
                    ],
                    [
                        'product_id' => $product->id,
                        'options'    => json_encode([1,4,7]),
                        'created_at' => new \DateTime()
                    ],
                ]);
            }
        }
    }
}
