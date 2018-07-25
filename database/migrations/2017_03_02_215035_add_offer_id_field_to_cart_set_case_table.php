<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferIdFieldToCartSetCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_set_case', function (Blueprint $table) {
            $table->integer('offer_id')->nullable()->index();
            $table->foreign('offer_id')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_set_case', function (Blueprint $table) {
            $table->dropIndex(['offer_id']);
            $table->dropColumn('offer_id');
        });
    }
}
