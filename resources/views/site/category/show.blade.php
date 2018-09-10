@extends('site.layouts.app')

<?php
    if ($current_tags !== [])
    {
        $title = $current_tags->title;
        $keywords = $current_tags->keywords;
        $h1 = $current_tags->h1;
        $description = $current_tags->desc;
        $text_up = $current_tags->text_up;
        $text_down = $current_tags->text_down;
    } else if($current_options !== []) {
        $title = ucfirst($current_options['device_name']) . ' ' . $current_options['color_name'] . ' ' . $category->title . ' ' . ucfirst($current_options['device_name']) . ' ' . lcfirst($current_options['case_name']);
        $keywords = implode(' ' . lcfirst($current_options['device_name']) . ' ' . $current_options['color_name'] . ' ' . lcfirst($current_options['case_name']) . ', ', mb_split(', ', $category->keywords)) . ' ' . lcfirst($current_options['device_name']) . ' ' . $current_options['color_name'] . ' ' . lcfirst($current_options['case_name']);
        $h1 = ucfirst( $category->h1 . ' - ' . $current_options['device_name']);
        $description = $category->description;
    }
    else {
        $title = $category->title;
        $keywords = $category->keywords;
        $description = $category->description;
        $h1 = ucfirst($category->h1);
    }
$str_breadcrumbs = 'ohcasey';
foreach($breadcrumbs as $b) {
    $str_breadcrumbs = $str_breadcrumbs . ' ' . mb_strtolower($b['name']);
}
    /*$keywords = explode(', ', $category->keywords);


    if (count($keywords) > 1) {
        $title = mb_substr(mb_strtoupper($keywords[1]), 0, 1) . mb_substr($keywords[1], 1);
        if ($category->parent == '36')
            $title = $title . ' ' . $str_breadcrumbs . ' - ' . ((count($keywords) >= 6) ? $keywords[5] : $keywords[count($keywords) - 1]);
        else 
            $title = $title . ' ' . $str_breadcrumbs . ' - ' . ((count($keywords) >= 5) ? $keywords[4] : $keywords[count($keywords) - 1]);

        
        switch ($category->parent) {
            case '2':
                $description = $category->h1 . '. Будь уникальной с авторскими чехлами для телефонов iPhone и Samsung ✔';
                $description = $description . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - ультрамодные чехлы с художественными картинками! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            case '36':
                $description = $category->h1 . '. Будь оригинальной с силиконовыми чехлами для телефона iPhone ✔';
                $description = $description . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - оригинальные чехлы разных оттенков! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            default:
                $description = $category->h1 . ' ' . $str_breadcrumbs . ' - ' . $keywords[0] . '. Ohcasey - ультрамодные чехлы! Доставка по всей России. ✆ +7 (965) 396-97-85'; 
                break;
        }
    }
    else {
        $title = mb_substr(mb_strtoupper($str_breadcrumbs), 0, 1) . mb_substr($str_breadcrumbs, 1);
        
        switch ($category->parent) {
            case '2':
                $description = $category->h1 . '. Будь уникальной с авторскими чехлами для телефонов iPhone и Samsung ✔';
                $description = $description . $str_breadcrumbs . '. Ohcasey - ультрамодные чехлы с художественными картинками! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            case '36':
                $description = $category->h1 . '. Будь оригинальной с силиконовыми чехлами для телефона iPhone ✔';
                $description = $description . $str_breadcrumbs . '. Ohcasey - оригинальные чехлы разных оттенков! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
            default:
                $description = $category->h1 . ' ' . $str_breadcrumbs . '. Ohcasey - ультрамодные чехлы! Доставка по всей России. ✆ +7 (965) 396-97-85';
                break;
        }
    }  */
?>

@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)

@section('content')
    <div class="inner @if($category->banner_image) inner--banner-top @endif">
        @if($category->banner_image)
        <div class="banner banner--top" style="background-image: url('{{ $category->banner_image }}')">
            <div class="container">
                <div class="banner__inner">
                    <h1 class="banner__title">{{$h1 !== "" ? $h1 : ($category->h1 ?: $category->title)}}</h1>
                    <div class="banner__text">{{$category->h2}}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="container" id="app">
            @if(!$category->banner_image)
            <div class="headline">
				@if($h1 == "")
					<h1 class="h1">{{$category->title}}</h1>
				@else
					<h1 class="h1">{{$h1}}</h1>
				@endif
            </div>
            @endif

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
                    <div class="filter" v-show="isCollection" v-cloak>
                        <button class="btn filter__btn js-popup-open" data-popup="filter" type="button" @click="selectColor(selectedPalette[$route.query.color], $route.query.color)">Фильтры</button>

                        <div class="filter__small-hidden" style="display: flex; align-items: center;">
                            <div class="filter__select">
                                <div class="select">
                                    <select class="js-select-item-device" data-placeholder="Телефон">
                                        <option v-for="v in devices" :value="v.value">@{{v.title}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter__select" v-if="$route.query['device'] != 'iphone' && $route.query['device'] != 'samsung'">
                                <div class="select">
                                    <select class="js-select-item-case" data-placeholder="Материал">
                                        <option v-for="v in cases" :value="v.case" :title="setCaseTitle(v.caption)">@{{v.caption}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filter__colors" v-if="$route.query['device'] != 'iphone' && $route.query['device'] != 'samsung'">
                                <div class="filter__label">Цвет</div>
                                <ul class="colors">
                                    <li class="colors__item" v-for="(v, index) in selectedPalette" @click="selectedColorIndex = index; selectColor(v, index)">
                                        <a class="colors__link colors__link js-color" :style="'background-color: ' + v" :class="[selectedColor == v ? 'is-active': '', v == '#FFFFFF' ? 'colors__link--white' : '']" :title="setColorTitle(v)"></a>
                                    </li>
                                </ul>
                            </div>

							<div class="filter__colors-mobile filter__select">
								<div class="select select-colors-mobile">
									<select id="colors-mobile"></select>
								</div>
							</div>

                            <div style="padding-left: 20px;">
                                <a class="btn" :href="routeString">Показать</a>
                            </div>

                        </div>

                        <div class="filter__sort js-sort">
                            <a @click="changeFilterParams('sort', '')" href="javascript:void(0)" class="filter__sort-link js-sort-link" :class="$route.query['sort'] == '' ? 'is-active' : ''">Новинки</a>
                            <a @click="changeFilterParams('sort', 'popular')" href="javascript:void(0)" class="filter__sort-link js-sort-link" :class="$route.query['sort'] == 'popular' ? 'is-active' : ''">Популярные</a>
                        </div>
                    </div>

                    <div class="loader" v-show="isLoading" v-cloak></div>

                    <div class="catalog catalog--inner @if($category->large_photos) catalog--collection js-collection @endif" v-show="!isLoading" v-cloak>
                        <div class="text text_top" id="text_up">
                        </div>
                        <div class="catalog--category">
                            @foreach ($children as $child)
                                @if ($category->id == $child->id && !empty($child))
                                    @foreach ($child->selfChildren()->get() as $ch)
                                        <a class="catalog__item" href="{{route('shop.slug', $child->url . '/' . $ch->slug)}}">
                                    <span class="catalog__img js-img">
                                        @if(!empty($ch) && $ch->image)
                                            @if($category->id == "36")
                                                <img src="{{$ch->image}}" alt="{{$ch->name}}" title="{{$ch->name}}">
                                            @else
                                                <img src="{{$ch->image}}" alt="{{$ch->name}}" title="{{$ch->name}} выбрать из коллекции и отредактировать свой неповторимый чехол.">
                                            @endif
                                        @else
                                            @if($category->id == "36")
                                                <img src="/img/layout/layout.png" alt="{{$ch->name}}" title="{{$ch->name}}">
                                            @else
                                                <img src="/img/layout/layout.png" alt="{{$ch->name}}" title="{{$ch->name}} выбрать из коллекции и отредактировать свой неповторимый чехол.">
                                            @endif
                                        @endif
                                    </span>
                                            <span class="catalog__title">{{$ch->name}}</span>
                                        </a>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        <a class="catalog__item" :href="setFullUrl(product)" v-for="product in data">
                            <span class="catalog__img js-img">
                                <span v-if="product.hit" class="catalog__label catalog__label--black">хит</span>
                                <span v-if="product.sale" class="catalog__label catalog__label--red">sale</span>
                                <img :src="product.main_photo_url" :alt="setImageAlt(product, '{{(count($keywords) > 1) ? $keywords[array_rand($keywords)] : "" }}')" :title="setImageTitle(product, '{{$str_breadcrumbs}}', '{{$category->parent}}')">
                            </span>
                            <span class="catalog__title" style="height:48px;">
                                @{{ product.name.substring(0, 22) }}
                                <span v-if="product.name.length > 21">...</span>
                            </span>
                            <span class="catalog__price price" v-if="!product.discount" v-show="category.display_price">@{{ product.price }}</span>
                            <span class="catalog__price" v-if="product.discount" v-show="category.display_price">
                                <span class="price price--old">@{{ product.discount }}</span>
                                <span class="price">@{{ product.price }}</span>
                            </span>
                        </a>
                    </div>


                    <ul class="pager-nav" v-show="!isLoading && paging.pages > 1" v-cloak>
                        <li class="pager-nav__item" v-for="pageNumber in paging.pages" :class="($route.query.page == pageNumber) || (!$route.query.page && pageNumber == 1) ? 'is-active' : ''">
                            <a href="javascript:void(0)" @click="changePage(pageNumber)" class="pager-nav__link">@{{ pageNumber }}</a>
                        </li>
                        <!--<li class="pager-nav__item"><a href="javascript:void(0)" @click="changePage(2)" class="pager-nav__link">2</a></li>
                        <li class="pager-nav__item"><a href="javascript:void(0)" @click="changePage(3)" class="pager-nav__link">3</a></li>
                        <li class="pager-nav__item"><a href="javascript:void(0)" @click="changePage(4)" class="pager-nav__link">4</a></li>
                        <li class="pager-nav__item"><a href="javascript:void(0)" class="pager-nav__link pager-nav__link--arrow">&gt;</a></li>-->
                    </ul>


                    <div class="tex text_down" id="text_down">
                    </div>
                </div>
            </div>

            <div class="popup js-popup popup--filter" data-popup="filter">
                <a href="#" class="popup__close popup__close--small js-popup-close"></a>
                <div class="filter">
                    <div class="filter__select">
                        <div class="select">
                            <select class="js-select-item-device-modal" data-placeholder="Телефон">
                                <option v-for="v in devices" :value="v.value">@{{v.title}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter__select" v-if="$route.query['device'] != 'iphone' && $route.query['device'] != 'samsung'">
                        <div class="select">
                            <select class="js-select-item-case-modal" data-placeholder="Материал">
                                <option v-for="v in cases" :value="v.case">@{{v.caption}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter__colors" v-if="$route.query['device'] != 'iphone' && $route.query['device'] != 'samsung'">
                        <div class="filter__label">Цвет</div>
                        <ul class="colors">
                            <li class="colors__item" v-for="(v, index) in selectedPalette" @click="selectedColorIndex = index; selectColor(v, index)">
                                <a class="colors__link colors__link js-color" :style="'background-color: ' + v" :class="[selectedColor == v ? 'is-active': '', v == '#FFFFFF' ? 'colors__link--white' : '']" :title="setColorTitle(v)"></a>
                            </li>
                        </ul>
                    </div>
					
                </div>
                <div class="popup__bottom">
                    <button type="button" class="btn" @click="applyFilters()">Применить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        window.category = {!! json_encode($category) !!}
        window.devices = {!! json_encode($devices) !!}
        window.colors = {!! json_encode($colors) !!}
        window.cases = {!! json_encode($cases) !!}
        window.options = {!! json_encode($options) !!}
        window.tags = {!! json_encode($tags) !!}
    </script>
    <script src="/js/frontcommons.js"></script>
    <script src="{{url('js/collection.js')}}"></script>
@endpush

@push('css')
    <style>
        [v-cloak] { display: none }
    </style>
@endpush
