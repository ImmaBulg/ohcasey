<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::transaction(function () {
            \Schema::create('special_order_items', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('order_id');
                $table->string('name');
                $table->float('price');
                $table->index(['order_id']);
                $table->timestamps();
            });

            \App\Models\Order::where(function ($query) {
                return $query->whereNotNull('special_item_name')
                    ->orWhereNotNull('special_item_price');
            })->each(function (\App\Models\Order $order) {
                if ($order->special_item_name || $order->special_item_price) {
                    \App\Models\SpecialOrderItem::create([
                        'name' => $order->special_item_name,
                        'price' => (float)$order->special_item_price,
                        'order_id' => $order->order_id
                    ]);
                }
            });

            \Schema::table('order', function (Blueprint $table) {
                $table->dropColumn(['special_item_name', 'special_item_price']);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::transaction(function () {
            \Schema::table('order', function (Blueprint $table) {
                $table->text('special_item_name')->nullable();
                $table->float('special_item_price')->nullable();
            });

            \App\Models\SpecialOrderItem::all()->each(function (\App\Models\SpecialOrderItem $specialItem) {
                $order = $specialItem->order;

                $order->special_item_price = $specialItem->price + $order->special_item_price;
                if ($order->special_item_name) {
                    $order->special_item_name .= ', ' . $specialItem->name;
                } else {
                    $order->special_item_name = $specialItem->name;
                }

                $specialItem->delete();
                $order->save();
            });

            \Schema::drop('special_order_items');
        });
    }
}
