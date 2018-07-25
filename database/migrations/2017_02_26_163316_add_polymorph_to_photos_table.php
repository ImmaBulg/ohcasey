<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPolymorphToPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->renameColumn('product_id', 'photoable_id');
            $table->string('photoable_type')->default('Product');
        });

        // DB::transaction(function () {
        //     \DB::table('photos')->update(['photoable_type' => 'Product']);
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->renameColumn('photoable_id', 'product_id');
            $table->dropColumn('photoable_type');
        });
    }
}
