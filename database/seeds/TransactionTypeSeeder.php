<?php

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! \App\Models\TransactionCategory::count()) {
            \App\Models\TransactionCategory::create([
                'name'        => 'Доходы',
                'is_incoming' => true
            ]);
        }

        if (! \App\Models\TransactionType::count()) {
            \App\Models\TransactionType::create([
                'name' => 'Оплаты онлайн',
                'transaction_category_id' => 1
            ]);
            \App\Models\TransactionType::create([
                'name' => 'Оплаты наличными',
                'transaction_category_id' => 1
            ]);
        }
    }
}
