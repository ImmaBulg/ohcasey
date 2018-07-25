<?php

namespace App\Http\Controllers;

use App\Models\Shop\Tag;
use App\Models\Shop\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class TagController extends Controller
{
    public function show($tag)
    {
        $tags = Tag::orderBy('order')->get();
        if (gettype($tag) == 'string')
            return redirect('/catalog', 301);
        $products = $tag->products()
            ->with(['background', 'photos'])
            ->active()
            ->hasOffer()
            ->orderBy('order')
            ->paginate(15);
        return view('site.tag.show', compact('tags', 'tag', 'products'));
    }
}
