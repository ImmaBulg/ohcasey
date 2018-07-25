<?php

use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	if(!DB::table('payment_methods')->count())
	        DB::table('payment_methods')->insert([
	            [
	            	'name' => 'online',
	            	'description' => "Оплата онлайн (банковские карты, платежные системы)"
	            ],
	            [
	            	'name' => 'cash',
	            	'description' => "Оплата курьеру наличными при получении"
	            ],
	            [
	            	'name' => 'manager',
	            	'description' => "Я хочу обсудить подробности заказа с менеджером"
	            ],
	        ]);
	   	if(!DB::table('delivery_payment_method')->count())
	   		DB::table('delivery_payment_method')->insert([
	   			[
	   				'delivery_name' => "showroom",
	   				'payment_methods_id' => 1,
	   			],
	   			[
	   				'delivery_name' => "showroom",
	   				'payment_methods_id' => 3,
	   			],
	   			[
	   				'delivery_name' => "courier_moscow",
	   				'payment_methods_id' => 1,
	   			],
	   			[	   			
	   				'delivery_name' => "courier_moscow",
	   				'payment_methods_id' => 2,
	   			],
	   			[	   			
	   				'delivery_name' => "courier_moscow",
	   				'payment_methods_id' => 3,
	   			],	   			
	   			[	   			
	   				'delivery_name' => "post",
	   				'payment_methods_id' => 1,
	   			],
	   			[	   			
	   				'delivery_name' => "post",
	   				'payment_methods_id' => 3,
	   			],
	   			[	   			
	   				'delivery_name' => "pickpoint",
	   				'payment_methods_id' => 1,
	   			],
	   			[	   			
	   				'delivery_name' => "pickpoint",
	   				'payment_methods_id' => 3,
	   			],
	   		]);
    }
}
