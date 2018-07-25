<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecialItemToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('order', function (Blueprint $table) {
            $table->text('special_item_name')->nullable();
            $table->float('special_item_price')->nullable();
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
            $table->dropColumn([
                'special_item_name',
                'special_item_price'
            ]);
        });
    }
}
