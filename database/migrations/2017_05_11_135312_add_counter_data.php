<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCounterData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('view_count')->default(0);
        });
        
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('order_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
        
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('order_count');
        });
    }
}
