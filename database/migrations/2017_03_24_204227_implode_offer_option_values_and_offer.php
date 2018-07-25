<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImplodeOfferOptionValuesAndOffer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('offers', function (Blueprint $table) {
            $table->boolean('active')->default(true);
            $table->jsonb('options')->nullable(true);
        });
        $count = 0;
        \App\Models\Shop\Offer::query()
            ->orderBy('id')
            ->chunk(750, function ($offers) use (&$count) {

                $valuesIds = $offers->lists('offer_option_value_id');
                /** @var \Illuminate\Database\Eloquent\Collection[]|\App\Models\Shop\Offer[] $offers */
                $offers = $offers->keyBy('offer_option_value_id');

                $offerOptionValues = \DB::table('offer_option_values')
                    ->whereIn('id', $valuesIds)
                    ->get();

                foreach ($offerOptionValues as $value) {
                    $offer = $offers[$value->id];
                    $offer->options = !is_array($value->value) ? json_decode($value->value) : $value->value;
                    $offer->active  = $value->active;
                    $offer->save();
                }

                $count += $offers->count();
                echo 'rdy - ', $count, "\n";
            });

        \Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['offer_option_value_id']);
        });
        \Schema::drop('offer_option_values');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // назад пути нет и не надо =)
    }
}
