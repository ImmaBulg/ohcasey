<?php

namespace App\Http\Controllers;

use App\Models\CatMeta;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\Shop\Option;
use App\Models\Shop\OptionValue;
use App\Models\Device;
use App\Models\Casey;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * Show category by slug hierarhy
     * @param  string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        $slutParts = explode('/', $slug);
        $lastSlugPart = end($slutParts);
        if ($lastSlugPart == 'futbolki' and count($slutParts) > 1)
            return redirect('/futbolki', 301);
        if ($lastSlugPart == 'kruzhki' and count($slutParts) > 1)
            return redirect('/kruzhki', 301);
        $category = Category::whereSlug($lastSlugPart)->first();
        if ($category == null or $category->slug != $lastSlugPart or $category->active == false) {
            return redirect('/collections', 301);
        }

        // colors of cases for filter
        $devices_colors = Device::select('device_name', 'device_colors')->get();
        $devices = $devices_colors->pluck('device_name');
        $colors = $devices->combine(
            $devices_colors->pluck('device_colors')
        );
        $options = OptionValue::select('value', 'title')->get();
        $options = $options->pluck('title', 'value');
        // cases of cases for filter
        $devices_cases = Device::select('device_name', 'device_cases')->get();
        $devices = $devices_cases->pluck('device_name');
        $cases = $devices->combine(
            $devices_cases->pluck('device_cases')
        );

        // cases of devices for filter
        $cases_caption = Casey::all()->pluck('case_caption', 'case_name');
        $devices_cases = Device::select('device_name', 'device_cases')->get();
        $cases = [];
        foreach ($devices_cases as $devices_case) {
            $cases[$devices_case->device_name] = $devices_case->device_cases;
            $devices_case_array = [];
            foreach ($devices_case->device_cases as $c) {
                $devices_case_array[] = (object)['case' => $c, 'caption' => $cases_caption[$c]];
            }
            $cases[$devices_case->device_name] = $devices_case_array;
        }

        // devices for filter
        $devices = Option::whereKey('device')->first()->values()->select('value', 'title')->orderBy('order', 'desc')->get();

        $children = $category->selfChildren->pluck('id')->push($category->id);

        $params = request()->only(['device', 'color', 'case', 'sort']);

        /** @var LengthAwarePaginator $products */
        $products = Product::query()
            ->whereHas('categories', function ($q) use ($children) {
                $q->whereIn('id', $children);
            })
            ->filter($params)
            ->paginate(15)->appends($params);

        $firstSlugPart = reset($slutParts);
        if (Category::whereSlug($firstSlugPart)->first() == null)
            return redirect('/collections', 301);
        $rootCategory = Category::whereSlug($firstSlugPart)->firstOrFail();

        $children = $rootCategory->selfChildren()->active()->get();

        $categoryParent = $category->selfParent;

        $breadcrumbs = [];

        if ($categoryParent) {
            if ($categoryParent->name !== 'Каталог')
                $breadcrumbs[] = ['href' => $categoryParent->url, 'name' => $categoryParent->name];
        }

        $breadcrumbs[] = ['name' => $category->name];

        $tmp = CatMeta::where([
            'cat_id' => $category->id,
        ])->get();
        $tags = [];
        $current_tags = [];
        $current_options = [];
        foreach ($tmp as $t) {
            $tags[$t->phone] = $t;
            if ($t->phone === $params['device']) {
                $current_tags = $t;
                $current_tags->title = $current_tags->title ?: $category->title;
                $current_tags->keywords = $current_tags->keywords ?: $category->keywords;
                $current_tags->h1 = $current_tags->h1 ?: $category->h1;
                $current_tags->desc = $current_tags->desc ?: $category->description;
                $current_tags->text_up = $current_tags->text_up ?: $category->text_top;
                $current_tags->text_down = $current_tags->text_down ?: $category->text_bottom;
            }
        }
        if ($params['device'] != '') {
            if ($params['device'] === 'iphone')
                $case = array_filter($cases['iphonex'], function ($item) use ($params) { return $item->case === $params['case']; });
            else if ($params['device'] === 'samsung')
                $case = array_filter($cases['sgs7e'], function ($item) use ($params) { return $item->case === $params['case']; });
            else
                $case = array_filter($cases[$params['device']], function ($item) use ($params) { return $item->case === $params['case']; });
            $current_options['device_name'] = $devices->filter(function($item) use ($params) { return $item->value === $params['device']; })->first()->title;
            $current_options['color_name'] = $colors->filter(function($item, $index) use ($params) { return $index === $params['device']; })->first()[$params['color']];
            $current_options['case_name'] = $case ? array_shift($case)->caption : '';
        }


        if (count($category->selfChildren) > 0 && $category->catalog_display_type == true) {
            return view('site.category.show-categories', compact('category', 'breadcrumbs', 'children', 'products'));
        } else {
            return view('site.category.show', compact('category', 'breadcrumbs', 'children', 'products', 'devices', 'colors', 'cases', 'params', 'options', 'tags', 'current_tags', 'current_options'));
        }

    }

}
