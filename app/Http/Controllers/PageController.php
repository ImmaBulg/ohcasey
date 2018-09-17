<?php

namespace App\Http\Controllers;

use App\Models\Shop\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Shop\Page;
use App\Models\Shop\Product;

class PageController extends Controller
{
    /**
     * @param Page $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($page)
    {
        $children = [];
        $category = null;
    	if ($page->slug === 'coll')
    		return redirect('/collections', 301);
        if ($page->slug === "catalog")
        {
            $category = Category::whereSlug('catalog')->first();
            if ($category)
                $children = $category->selfChildren()->active()->get();
            $bestsellers = $this->getTopItems($offset = 0, $limit = 8);
            if ($bestsellers->count()) 
            {
                $position = '<h2 style="text-align:center">ТОП самых милых, красивых чехлов для телефонов</h2>';
                $add_str = '<div class="catalog" style="margin: 20px 0px 20px 0px">';
                foreach ($bestsellers as $bs)
                {
                    $add_str = $add_str . '<a class="catalog__item" href="' . route("shop.product.show", $bs->id) . '">
                    <span class="catalog__img"><img src="' . $bs->mainPhoto() . '" alt="' . $bs->name . '" title="' . $bs->name . ' заказать">></span>
                    <span class="catalog__title">' . $bs->name . '</span>
                    </a>';
                }
                $add_str .= '</div>';
                $page->content = str_replace($position, $position . $add_str, $page->content);
            }
        }
        if (mb_substr_count($page['content'], '<div class="inner">') == 0)
            $page['content'] = '<div class="inner"><div class="container">' . $page['content'] . '</div></div>';
        return view('site.shop.pages.show', ['page' => $page, 'children' => $children, 'category' => $category]);
    }

    private function getTopItems($offset = 0, $limit = 12)
	{
		$top = Product::with([ 'background',  'photos' => function($q){
				return $q->orderBy('updated_at', 'desc');
			}])
			->whereHas('categories', function ($query) {
				$query->where('category_id', 15);
			})
			->active()
			->hasOffer()
			->orderBy('order')
			->limit($limit)
			->skip($offset)
			->latest()
			->get();
		return $top;
	}
}
