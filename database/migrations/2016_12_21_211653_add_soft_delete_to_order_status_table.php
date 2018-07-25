<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::transaction(function () {
            // у первичного ключа не был установлен autoincrement
            \DB::statement('CREATE SEQUENCE status_id_seq');
            \DB::statement('ALTER SEQUENCE status_id_seq RESTART WITH 8');
            \DB::statement('ALTER TABLE order_status ALTER status_id SET DEFAULT NEXTVAL(\'status_id_seq\')');


            \Schema::table('order_status', function (Blueprint $table) {
                $table->integer('sort')->default(500);
                $table->softDeletes();
            });

            \App\Models\OrderStatus::all()->each(function (\App\Models\OrderStatus $status) {
                $status->sort = 500;
                $status->save();
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
        \DB::transaction(function () {
            \DB::statement('ALTER TABLE order_status ALTER status_id DROP DEFAULT');
            \DB::statement('DROP SEQUENCE status_id_seq');

            \Schema::table('order_status', function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropColumn('sort');
            });
        });
    }
}
