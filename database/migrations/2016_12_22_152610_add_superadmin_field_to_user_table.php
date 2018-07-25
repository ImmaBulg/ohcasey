<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuperadminFieldToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function ($table) {
            $table->boolean('superadmin')->default(false);
            $table->string('login')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function ($table) {
            $table->dropColumn('superadmin');
        });
        DB::transaction(function () {
            \DB::statement('ALTER TABLE "user" DROP CONSTRAINT user_login_unique');
        });
    }
}
