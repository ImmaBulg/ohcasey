<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::transaction(function () {
            \Schema::create('sms_templates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->longText('template');
                $table->smallInteger('before_order_status_id')->nullable(true);
                $table->smallInteger('after_order_status_id');

                $table->index('before_order_status_id');
                $table->index('after_order_status_id');

                $table->foreign('before_order_status_id')->references('status_id')->on('order_status');
                $table->foreign('after_order_status_id')->references('status_id')->on('order_status');

                $table->unique([
                    'before_order_status_id',
                    'after_order_status_id',
                ]);

                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('sms_templates');
    }
}
