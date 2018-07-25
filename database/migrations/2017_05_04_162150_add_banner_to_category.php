<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBannerToCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('h2')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('text_top')->nullable();
            $table->text('text_bottom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('h2');
            $table->dropColumn('banner_image');
            $table->dropColumn('text_top');
            $table->dropColumn('text_bottom');
        });
    }
}
