<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_option_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('related_option_id');
            $table->foreign('related_option_id')->references('id')->on('related_options');
            $table->jsonb('value');
            $table->boolean('active')->default(true);
        // });

        // DB::transaction(function () {
        //     \DB::statement('ALTER TABLE related_option_values ADD COLUMN option_value_ids integer[][]');
        // });

        // Schema::table('related_option_values', function (Blueprint $table) {
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
        Schema::drop('related_option_values');
    }
}
