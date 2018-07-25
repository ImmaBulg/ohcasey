<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('code')->nullable()->unique()->index();
            $table->float('price')->default(0);
            $table->float('discount')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->integer('draft_user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('category_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
        Schema::drop('category_product');
    }
}
