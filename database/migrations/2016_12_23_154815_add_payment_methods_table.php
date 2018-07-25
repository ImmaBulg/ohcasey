<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
        });

        Schema::create('delivery_payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_methods_id');
            $table->string('delivery_name');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->unsignedInteger('payment_methods_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_methods');

        Schema::drop('delivery_payment_method');

        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('payment_methods_id');
        });
    }
}
