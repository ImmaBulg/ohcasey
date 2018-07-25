@extends('site.layouts.app')

@section('title', $tag->title)
@section('keywords', $tag->keywords)
@section('description', $tag->description)

@section('content')
    <div class="inner">
        <div class="container">
            <div class="headline">
                <h1 class="h1">{{$tag->title}}</h1>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 hidden-sm hidden-xs">
                    @if (count($tags))
                        <ul class="left-nav">
                            @foreach ($tags as $tagItem)
                                <li class="left-nav__item{{$tagItem->slug == $tag->slug ? ' is-active' : ''}}">
                                    <a class="left-nav__link" href="{{route('shop.slug', $tagItem->slug)}}">{{$tagItem->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="catalog catalog--inner @if($tag->large_photos) catalog--collection js-collection @endif">
                        @foreach ($products as $product)
                            <a class="catalog__item" href="{{route('shop.product.show', $product->id)}}">
                                <?php
                                    $previewPhotoImage = $product->mainPhoto();
                                ?>
                                @if ($previewPhotoImage)
                                    <span class="catalog__img @if($tag->large_photos) js-img @endif">
                                            <img src="{{$previewPhotoImage}}" alt="">
                                    </span>
                                @endif
                                <span class="catalog__title" style="height:48px;">{{str_limit($product->name, $limit = 22, $end = '...')}}</span>
                                @if ($tag->display_price)
                                    <span class="catalog__price price">{{$product->price}}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @include('site.pagination.default', ['paginator' => $products])
        </div>
    </div>
@endsection