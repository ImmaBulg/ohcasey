<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount');
            $table->dateTime('datetime');
            $table->longText('comment')->nullable();

            $table->unsignedInteger('transaction_type_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('payment_id')->nullable();

            $table->index('transaction_type_id');
            $table->index('order_id');
            $table->index('payment_id');

            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('order_id')->references('order_id')->on('order');
            $table->foreign('payment_id')->references('id')->on('payments');
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
        \Schema::drop('transactions');
    }
}
