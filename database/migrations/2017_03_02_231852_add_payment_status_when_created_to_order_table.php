<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentStatusWhenCreatedToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('order', function (Blueprint $table) {
            $table->boolean('processed_online_payment')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('order', function (Blueprint $table) {
            $table->dropColumn(['processed_online_payment']);
        });
    }
}
