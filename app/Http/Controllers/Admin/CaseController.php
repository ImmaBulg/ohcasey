<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Shop\OptionGroup;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Product;
use App\Models\Device;
use App\Models\Casey;

class CaseController extends Controller
{
    public function index()
    {
        return view('admin.generate');
    }

    public function generate()
    {
        OptionValue::whereHas('option', function ($q) {
            $q->where('key', 'case');
        })->get()->each(function ($case) {
            if (Casey::where('case_name', $case->value)->count() == 0) {
                Casey::create(['case_name' => $case->value, 'case_caption' => $case->title]);
            }
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

        $products = Product::where('option_group_id', OptionGroup::ID_CASE_GROUP)->get();
        return $products;
    }
}
