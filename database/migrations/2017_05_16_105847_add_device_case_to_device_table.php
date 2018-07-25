<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Casey;
use App\Models\Device;

class AddDeviceCaseToDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device', function (Blueprint $table) {
            $table->text('device_cases')->nullable();
        });

        $cases = Casey::all();

        Device::all()->each(function ($device) use ($cases) {
            $device_path = storage_path('app/device/' . $device->device_name);
            if (file_exists($device_path)) {
                $device_cases = [];
                foreach ($cases as $case) {
                    $case_path = $device_path . '/case/' . $case->case_name . '.png';
                    if (file_exists($case_path)) {
                        $device_cases[] = $case->case_name;
                    }
                }
                $device->device_cases = $device_cases;
                $device->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('device_cases');
        });
    }
}
