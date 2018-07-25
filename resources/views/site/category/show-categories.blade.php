@extends('site.layouts.app')

<?php 
    $last_words = [['выбрать', 'заказать', 'посмотреть', 'купить', 'подобрать'], 
        ['оригинальный', 'уникальный', 'бесподобный', 'стильный', 'красивый', 'неповторимый'],
        ['чехол', 'дизайн', 'цвет и оттенок']];

    $keywords = explode(', ', $category->keywords);
    $str_breadcrumbs = 'ohcasey';
    foreach($breadcrumbs as $b) {
        $str_breadcrumbs = $str_breadcrumbs . ' ' . mb_strtolower($b['name']);
    }

    if (count($keywords) > 1) {
        $title = mb_substr(mb_strtoupper($keywords[1]), 0, 1) . mb_substr($keywords[1], 1);
        if (count($keywords) >= 5)
            $title = $title . ' ' . $str_breadcrumbs . ' - ' . $keywords[4];
        else 
            $title = $title . ' ' . $str_breadcrumbs . ' - ' . $keywords[count($keywords) - 1];

        switch ($category->id) {
            case '2':
                $description = $category->h1 . '. Будь уникальной с авторскими чехлами для телефонов iPhone и Samsung ✔';
                $description = $description . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - ультрамодные чехлы с художественными картинками! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            case '36':
                $description = $category->h1 . '. Будь оригинальной с силиконовыми чехлами для телефона iPhone ✔';
                $description = $description . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - оригинальные чехлы разных оттенков! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            default:
                $description = $category->h1 . ' ' . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - ультрамодные чехлы! Доставка по всей России. ✆ +7 (965) 396-97-85';; 
                break;
        }
        $image_alt = $keywords[array_rand($keywords)];
        $image_alt = mb_substr(mb_strtoupper($image_alt), 0, 1) . mb_substr($image_alt, 1);
        $image_title = mb_substr(mb_strtoupper($str_breadcrumbs), 0, 1) . mb_substr($str_breadcrumbs, 1);
    }
    else {
        $title = mb_substr(mb_strtoupper($str_breadcrumbs), 0, 1) . mb_substr($str_breadcrumbs, 1);
        switch ($category->id) {
            case '2':
                $description = $category->h1 . '. Будь уникальной с авторскими чехлами для телефонов iPhone и Samsung ✔';
                $description = $description . $str_breadcrumbs . '. Ohcasey - ультрамодные чехлы с художественными картинками! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            case '36':
                $description = $category->h1 . '. Будь оригинальной с силиконовыми чехлами для телефона iPhone ✔';
                $description = $description . $str_breadcrumbs . '. Ohcasey - оригинальные чехлы разных оттенков! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            default:
                $description = $category->h1 . ' ' . $str_breadcrumbs . '. Ohcasey - ультрамодные чехлы! Доставка по всей России. ✆ +7 (965) 396-97-85';; 
                break;
        }
        $image_alt = '';
        $image_title = '';
    }
?>

@section('title', $title)
@section('keywords', $category->keywords)
@section('description', $description)

@section('content')
    <div class="inner">
        <div class="container">
            <div class="headline">
                <h1 class="h1">{{$category->h1}}</h1>
            </div>
            @include('site.shop.partial.breadcrumbs')
            <div class="row">
                @include('site.shop.partial.left-menu')
                <!-- <div class="col-lg-2 col-md-2 hidden-sm hidden-xs">
                    @if (count($children))
                        <ul class="left-nav">
                            @foreach ($children as $child)
                                <li class="left-nav__item{{$category->slug == $child->slug ? ' is-active' : ''}}">
                                    <a class="left-nav__link" href="{{route('shop.slug', $child->url)}}">{{$child->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div> -->
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    @if (count($children))
                      <div class="catalog catalog--inner catalog--category js-collection-category">
                        @foreach ($children as $child)
                          <a class="catalog__item" href="{{route('shop.slug', $child->url)}}">
                            <span class="catalog__img js-img">
                            @if(!empty($child) && $child->image)
                                @if($category->id == "36")
                                    <img src="{{$child->image}}" alt="{{$image_alt}} {{$child->name}}" title="{{$image_title}} {{$child->name}} {{$last_words[0][array_rand($last_words[0])]}} свой {{$last_words[1][array_rand($last_words[1])]}} {{$last_words[2][array_rand($last_words[2])]}}">
                                @else
                                    <img src="{{$child->image}}" alt="{{$image_alt}} {{$child->name}}" title="{{$image_title}} {{$child->name}} выбрать из коллекции и отредактировать свой неповторимый чехол.">
                                @endif
                            @else
                                @if($category->id = "36")
                                    <img src="/img/layout/layout.png" alt="{{$image_alt}} {{$child->name}}" title="{{$image_title}} {{$child->name}} {{$last_words[0][array_rand($last_words[0])]}} свой {{$last_words[1][array_rand($last_words[1])]}} {{$last_word[2][array_rand($last_word[2])]}}">
                                @else
                                    <img src="/img/layout/layout.png" alt="{{$image_alt}} {{$child->name}}" title="{{$image_title}} {{$child->name}} выбрать из коллекции и отредактировать свой неповторимый чехол.">
                                @endif
                            @endif
                            </span>
                            <span class="catalog__title">{{$child->name}}</span>
                          </a>
                        @endforeach
                      </div>
                    @endif
                </div>
            </div>
            <!--@include('site.pagination.default', ['paginator' => $products])-->
        </div>
    </div>
@endsection