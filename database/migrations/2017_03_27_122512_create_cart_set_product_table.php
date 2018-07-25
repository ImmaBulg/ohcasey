<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartSetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('cart_set_product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('offer_id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('item_count');
            $table->unsignedInteger('cart_set_id')->nullable();
            $table->float('item_cost');
            $table->string('item_sku');

            $table->index('offer_id');
            $table->foreign('offer_id')->references('id')->on('offers');
            $table->index('cart_id');
            $table->foreign('cart_id')->references('cart_id')->on('cart');
            $table->index('cart_set_id');
            $table->foreign('cart_set_id')->references('cart_set_id')->on('cart_set');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('cart_set_product');
    }
}
