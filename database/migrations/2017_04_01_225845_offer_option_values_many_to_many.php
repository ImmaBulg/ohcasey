<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OfferOptionValuesManyToMany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_option_value', function (Blueprint $table) {
            $table->integer('offer_id')->index();
            $table->integer('option_value_id')->index();
        });
        
        DB::transaction(function () {
            $offers = DB::table('offers')->select('id', 'options')->get();
            foreach ($offers as $offer) {
                foreach (json_decode($offer->options) as $option_value_id) {
                    DB::table('offer_option_value')->insert([
                        'offer_id' => $offer->id,
                        'option_value_id' => $option_value_id
                    ]);
                }
            }
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->jsonb('options')->nullable();
        });

        DB::transaction(function () {
            $offers = DB::table('offers')->select('id')->get();
            foreach ($offers as $offer) {
                $values = DB::table('offer_option_value')->where('offer_id', $offer->id)->get(['option_value_id']);
                $values_array = array_column($values, 'option_value_id');
                DB::table('offers')->where('id', $offer->id)->update(['options' => json_encode($values_array)]);
            }
        });

        Schema::drop('offer_option_value');
    }
}
