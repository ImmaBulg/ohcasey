<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryInfoToCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('cart', function (Blueprint $table) {
            $table->string('delivery_name')->nullable();
            $table->float('delivery_amount')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['delivery_name']);
            $table->dropColumn(['delivery_amount']);
        });
    }
}
