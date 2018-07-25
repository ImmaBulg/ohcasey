<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelsShopTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('title')->nullable();
            $table->string('h1')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('display_price')->default(false);
            $table->boolean('large_photos')->default(false);
            $table->integer('order')->default(0);
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->timestamps();
        });
        Schema::create('product_tag', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
        Schema::drop('product_tag');
    }
}
