<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('order_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->text('short_code');
            $table->text('field_name')->nullable(true);

            $table->longText('description');
            $table->longText('old_value')->nullable(true);
            $table->longText('new_value')->nullable(true);

            $table->unsignedInteger('user_id')->nullable(true);
            $table->unsignedInteger('order_id');

            $table->index('user_id');
            $table->index('order_id');

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
        \Schema::drop('order_logs');
    }
}
