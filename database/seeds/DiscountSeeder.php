<?php

use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ItemPrice::create([
            'user_group' => 'small price',
            'item_sku'   => 'case',
            'item_cost'  => 1250.0,
        ]);
        \App\Models\ItemPrice::create([
            'user_group' => 'usually price',
            'item_sku'   => 'case',
            'item_cost'  => 1500.0,
        ]);
        \App\Models\ItemPrice::create([
            'user_group' => 'high price',
            'item_sku'   => 'case',
            'item_cost'  => 1750.0,
        ]);
    }
}
