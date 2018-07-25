<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('transaction_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('is_incoming')->default(false);
            $table->unique('name');
            $table->timestamps();
        });

        \Schema::table('transaction_types', function (Blueprint $table) {
            $table->unsignedInteger('transaction_category_id')->nullable();
            $table->index('transaction_category_id');
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('transaction_types', function (Blueprint $table) {
            $table->dropColumn('transaction_category_id');
        });
        \Schema::drop('transaction_categories');
    }
}
