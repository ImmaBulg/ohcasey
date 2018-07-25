<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Shop\Page;

class AddMetatags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Page::where('slug','/')->count() == 0){
            Page::create(
                [
                    'slug' => '/',
                    'title' => 'Главная',
                    'content' => '',
                ]
            );
        }
        
        Schema::table('products', function (Blueprint $table) {
            $table->string('h1')->default('');
            $table->string('keywords')->default('');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->string('h1')->default('');
        });
        
        Schema::table('pages', function (Blueprint $table) {
            $table->string('h1')->default('');
            $table->string('keywords')->default('');
            $table->string('description')->default('');
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
            $table->dropColumn('h1');
            $table->dropColumn('keywords');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('h1');
        });
        
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('h1');
            $table->dropColumn('keywords');
            $table->dropColumn('description');
        });
    }
}
