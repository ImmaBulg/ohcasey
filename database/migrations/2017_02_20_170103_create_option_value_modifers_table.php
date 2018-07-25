<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionValueModifersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_value_modifers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_value_id');
            $table->foreign('option_value_id')->references('id')->on('option_values');
            $table->string('price_prefix', 1);
            $table->float('price')->default(0);
            $table->softDeletes();
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
        Schema::drop('option_value_modifers');
    }
}
