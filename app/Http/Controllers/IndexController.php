<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Shop\Product;
use App\Models\Shop\Page;

class IndexController extends Controller
{
    public function show(Request $request)
	{
		$page = Page::whereSlug('/')->first();
		
		$bestsellers = $this->getTopItems();
		
		if ($request->input('version') == 'old') {
            return view('site.home', compact('bestsellers', 'menu', 'page'));
        } else {
            return view('site.home-new', compact('bestsellers', 'menu', 'page'));
        }
	}
	
	public function topItems(Request $request, $offset)
	{
		$top = $this->getTopItems($offset, $request->input('limit', 12));
		$itemsCount = count($top);
		$result = '';
		switch($request->input('index')){
			default:
				$result = $this->getIndexTopMarkup($top);
				break;
		}
		return json_encode(compact('result', 'itemsCount'));
	}
	
	private function getIndexTopMarkup($topItems)
	{
		$res = '';
		foreach($topItems as $topItem){
			$res .= '<a class="catalog__item" href="'. route('shop.product.show', [$topItem->id, 'sort' => '', 'device' => 'iphonex', 'case' => 'silicone', 'color' => '1']) .'">
                    <span class="catalog__img"><img src="'.$topItem->mainPhoto().'" alt="Купить чехол для телефона '. $topItem->name . '" title="' . $topItem->name . ' заказать"></span>
                    <span class="catalog__title">'.$topItem->name.'</span>
                </a>';
		}
		
		return $res;
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
