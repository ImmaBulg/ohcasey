<?php

namespace App\Http\Controllers;

use App\Models\Background;
use App\Models\BackgroundGroup;
use App\Models\Device;
use App\Models\Font;
use App\Models\Smile;
use App\Models\SmileGroup;
use App\Ohcasey\FontInfo;
use App\Ohcasey\Ohcasey as Oh;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class ControlPanel
 * @package App\Http\Controllers
 */
class ControlPanel extends Controller
{
    /**
     * Get device control panel
     * @param Oh $o
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function device(Oh $o, Request $request)
    {
        return view('site.control-panel.device', [
            'devices' => Device::query()->orderBy('device_order')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deviceHelper(Request $request)
    {
        return view('site.control-panel.device-helper', [
        ]);
    }

    /**
     * Get case
     * @param Oh $o
     * @param Request $request
     * @return string
     */
    public function casey(Oh $o, Request $request)
    {
        // Useful variables
        $current = $request->input('current', null);
        $device = preg_replace("/[^0-9a-zA-Z]/", "", array_get($current, 'DEVICE.device', ''));
        $mDevice = Device::find($device);
        if (!$mDevice) return response('Not found', 404);

        $casePath = storage_path('app/device/'.$device.'/case');
        $colorsPath = storage_path('app/device/'.$device.'/color');

        // Scan available colors
        $colors = [];
        $colorsFiles = scandir($colorsPath);
        foreach ($colorsFiles as $f) {
            if ($f !== '.' && $f !== '..') {
                $c = explode('_', basename($f, '.png'));
                if (isset($c[1])) {
                    $colors[$c[0]][] = $c[1];
                }
            }
        }

        // Scan available cases
        $cases = [];
        $casesFiles = scandir($casePath);
        foreach ($casesFiles as $f) {
            if ($f !== '.' && $f !== '..') {
                $c = basename($f, '.png');
                $cases[] = [
                    'name' => $c,
                    'colors' => array_get($colors, $c, [])
                ];
            }
        }

        return view('site.control-panel.case', [
            'device' => $device,
            'cases' => $cases,
        ]);
    }

    /**
     * Get case helper
     * @param Oh $o
     * @param Request $request
     * @return string
     */
    public function caseyHelper(Oh $o, Request $request)
    {
        // Settings
        $current = $request->input('current', null);
        $device = preg_replace("/[^0-9a-zA-Z]/", "", array_get($current, 'DEVICE.device', ''));
        $mDevice = Device::find($device);
        return $mDevice ? view('site.control-panel.case-helper', ['colors' => $mDevice->device_colors]) : response('Not found', 404);
    }

    /**
     * Get backgrounds
     * @param Request $request
     * @return string
     */
    public function bg(Request $request)
    {
        return view('site.control-panel.background', [
            'categories' => BackgroundGroup::orderBy('order')->orderBy('name')->get(),
        ]);
    }

    /**
     * Get bg list
     * @param $cat
     * @param Request $request
     * @return string
     */
    public function bgList($cat, Request $request)
    {
        $backgrounds = Background::query()->with('backgroundGroups');
        if (strtolower($cat) != strtolower(Oh::GROUP_ALL)) {
            $backgrounds->whereHas('backgroundGroups', function ($query) use ($cat) {
                return $query->whereName($cat);
            });
        }

        return view('site.control-panel.background-list', [
            'backgrounds' => $backgrounds->orderBy('order', 'desc')->get(),
        ]);
    }

    /**
     * Get backgrounds helper
     * @param Request $request
     * @return string
     */
    public function bgHelper(Request $request)
    {
        return view('site.control-panel.background-helper', [
        ]);
    }

    /**
     * Get smiles
     * @param Request $request
     * @return string
     */
    public function smile(Request $request)
    {
        // Response
        return view('site.control-panel.smile', [
            'category' => SmileGroup::all(),
        ]);
    }

    /**
     * Get smile list
     * @param $cat
     * @param Request $request
     * @return string
     */
    public function smileList($cat, Request $request)
    {
        // Response
        return view('site.control-panel.smile-list', [
            'smiles' => Smile::whereRaw('(jsonb_exists(smile_group, ?) or lower(?) = lower(?))', [$cat, $cat, Oh::GROUP_ALL])->orderBy('smile_ts', 'desc')->get(),
        ]);
    }

    /**
     * Get smile helper
     * @param Request $request
     * @return string
     */
    public function smileHelper(Request $request)
    {
        return view('site.control-panel.smile-helper', [
        ]);
    }

    /**
     * Get fonts
     * @param Oh $o
     * @param Request $request
     * @return string
     */
    public function font(Oh $o, Request $request)
    {
        $fonts = Font::all();
        foreach ($fonts as $font) {
            if (!$font->font_caption) {
                $font->font_caption = (new FontInfo(storage_path('app/fonts/'.$font->font_name)))->getFontName();
                $font->save();
            }
        }

        // Response
        return view('site.control-panel.font', [
            'fonts' => Font::all(),
        ]);
    }

    /**
     * Get font helper
     * @param Request $request
     * @return string
     */
    public function fontHelper(Request $request)
    {
        return view('site.control-panel.font-helper', [
            'colors' => [
                "#000000", "#989898", "#ffffff", "#181c90", "#3539a6",
                "#676abc", "#0d7cc3", "#6eafdb", "#d00349", "#e26891",
                "#a0a61a", "#d5dd23", "#5a9125", "#9bbd7c", "#9380fe",
                "#98e7dd", "#2ea2cd", "#abdaeb", "#90278d", "#9c1f63",
                "#550460", "#e91000", "#7a4407", "#efa3bd", "#f6d1dd",
                "#fdba33", "#fed685", "#fff336", "#ef5a29", "#f9cbbc",
                "#3d0d73", "#7a1ae4", "#00460a", "#668f6c", "#a2bed8",
                "#ecca48", "#d60c00", "#d4ef10", "#c1c66c", "#ae8e6a",
                "#f2805a", "#d57307", "#c4deec", "#da4437", "#0f5aba",
                "#3212d6", "#29efd8", "#c61f83", "#cbcbcb"
            ],
        ]);
    }
}
