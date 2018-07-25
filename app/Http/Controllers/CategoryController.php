<?php

namespace App\Http\Controllers;

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
                array_push($breadcrumbs, ['href' => $categoryParent->url, 'name' => $categoryParent->name]);
        }

        array_push($breadcrumbs, ['name' => $category->name]);

        if (count($category->selfChildren) > 0 && $category->catalog_display_type == true) {
            return view('site.category.show-categories', compact('category', 'breadcrumbs', 'children', 'products'));
        } else {
            return view('site.category.show', compact('category', 'breadcrumbs', 'children', 'products', 'devices', 'colors', 'cases', 'params', 'options'));
        }

    }

}
