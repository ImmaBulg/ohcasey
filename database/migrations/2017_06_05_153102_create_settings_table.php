<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->text('value');
            $table->string('title');
            $table->string('type');
            $table->string('group');
        });

        DB::table('settings')->insert([
            [
                'key' => 'action_text',
                'value' => '<span style="color:red"> Акция 1+1=3.</span> Закажи 2 чехла и получи третий в подарок! Промокод HAPPY3 только до конца Мая!',
                'title' => 'Текст акции',
                'type' => 'text',
                'group' => 'action',
            ],
            [
                'key' => 'action_display',
                'value' => 1,
                'title' => 'Отображать акцию?',
                'type' => 'boolean',
                'group' => 'action',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
