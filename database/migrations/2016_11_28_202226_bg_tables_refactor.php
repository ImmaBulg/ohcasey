<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BgTablesRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @throws
     * @return void
     */
    public function up()
    {
        \DB::beginTransaction();
        try {
            // копируем всё из bg/bg_group в новые таблицы
            \Schema::create('backgrounds', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique()->index();
                $table->timestamps();
            });

            \Schema::create('background_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique()->index();
                $table->integer('order')->nullable(true);
                $table->timestamps();
            });

            \Schema::create('backgrounds_background_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('background_group_id');
                $table->unsignedInteger('background_id');

                $table->foreign('background_id')->references('id')->on('backgrounds');
                $table->foreign('background_group_id')->references('id')->on('background_groups');
            });

            array_map(function (stdClass $group) {
                \App\Models\BackgroundGroup::create([
                   'name'  => data_get($group, 'bg_group_name'),
                   'order' => data_get($group, 'bg_group_order'),
                ]);
            }, \DB::table('bg_group')->get());

            array_map(function (stdClass $stdBackground) {
                $background = \App\Models\Background::create([
                   'name'  => data_get($stdBackground, 'bg_name'),
                ]);
                $groupNames = json_decode($stdBackground->bg_group, true);
                if ($groupNames) {
                    $groupIds = \App\Models\BackgroundGroup::whereIn('name', $groupNames)->get()->pluck('id')->toArray();
                    $background->backgroundGroups()->sync($groupIds);
                }
                if ($stdBackground->bg_ts) {
                    $background->updated_at = $background->bg_ts;
                    $background->save();
                }
            }, \DB::table('bg')->get());

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @throws
     * @return void
     */
    public function down()
    {
        \Schema::drop('backgrounds_background_groups');
        \Schema::drop('background_groups');
        \Schema::drop('backgrounds');
    }
}
