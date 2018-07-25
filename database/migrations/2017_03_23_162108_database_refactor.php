<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::rename('related_options', 'option_groups');
        \Schema::rename('option_related_option', 'option_option_group');
        \Schema::rename('related_option_values', 'offer_option_values');

        DB::statement("ALTER TABLE offer_option_values RENAME related_option_id TO option_group_id");

        \Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('related_option_value_id', 'offer_option_value_id');
        });

        \Schema::table('option_option_group', function (Blueprint $table) {
            $table->renameColumn('related_option_id', 'option_group_id');
        });

        \Schema::table('products', function (Blueprint $table) {
            $table->integer('option_group_id')->default(1);
            $table->foreign('option_group_id')->references('id')->on('option_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::rename('option_groups', 'related_options');
        \Schema::rename('option_option_group', 'option_related_option');
        \Schema::rename('offer_option_values', 'related_option_values');

        DB::statement("ALTER TABLE related_option_values RENAME option_group_id TO related_option_id");


        \Schema::table('offers', function (Blueprint $table) {
            $table->renameColumn('offer_option_value_id', 'related_option_value_id');
        });

        \Schema::table('option_related_option', function (Blueprint $table) {
            $table->renameColumn('option_group_id', 'related_option_id');
        });

        \Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['option_group_id']);
        });
    }
}
