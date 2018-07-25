<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CartSetCase; 
use App\Models\CartSetProduct; 

class UpdateOrderCountInOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CartSetCase::all()->each(function ($set) {
            if($set->offer){
                ++$set->offer->order_count;
                $set->offer->save();
            }
        });
        
        CartSetProduct::all()->each(function ($set) {
            if($set->offer){
                ++$set->offer->order_count;
                $set->offer->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
