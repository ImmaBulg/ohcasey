@extends('site.layouts.app')

@section('title', $page->title)
@section('keywords', $page->keywords)
@section('description', $page->description)

@section('content')
<div class="container">
    <a href="/collections/florals" class="main-banner" style="background-image: url('/img/layout/main.jpg')">
        <div class="main-banner__inner">
        </div>
    </a>
    <div class="collection">
        <div class="collection__column">
            <div class="collection__item js-col-item">
                <a class="collection__link" href="/collections/fashion">
                    <span class="collection__img">
                        <span class="collection__inner-img"><img src="/img/layout/ind_fashion.jpg" alt=""></span>
                        <span class="collection__caption">Fashion</span>
                    </span>
                    <span class="collection__title" href="/collections/fashion">Spring-Summer 2017</span>
                </a>
                <a class="collection__link-col">Посмотреть <span>коллекцию &rarr;</span></a>
            </div>

            <div class="collection__item js-col-item">
                <a class="collection__link" href="/collections/love">
                    <span class="collection__img">
                        <span class="collection__inner-img"><img src="/img/layout/ind_love.jpg" alt=""></span>
                        <span class="collection__caption">Pure love</span>
                    </span>
                    <span class="collection__title">Pure love. Трогательные кейсы</span>
                </a>
                <a class="collection__link-col" href="/collections/love">Посмотреть <span>коллекцию &rarr;</span></a>
            </div>

            <div class="collection__item js-col-item">
                <a class="collection__link" href="/collections/travel">
                    <span class="collection__img">
                        <span class="collection__inner-img"><img src="/img/layout/ind_travel.jpg" alt=""></span>
                        <span class="collection__caption">Travel</span>
                    </span>
                    <span class="collection__title">Travel the World</span>
                </a>
                <a class="collection__link-col" href="/collections/travel">Посмотреть <span>коллекцию &rarr;</span></a>
            </div>
        </div>

        <div class="collection__column">
            <div class="collection__item js-col-item">
                <a class="collection__link" href="/collections/art">
                    <span class="collection__img">
                        <span class="collection__inner-img"><img src="/img/layout/ind_floral.jpg" alt=""></span>
                        <span class="collection__caption">Beauty</span>
                    </span>
                    <span class="collection__title">Feel the Beauty</span>
                </a>
                <a class="collection__link-col" href="/collections/art">Посмотреть <span>коллекцию &rarr;</span></a>
            </div>

            <div class="collection__item js-col-item">
                <a class="collection__link" href="/collections/patterns">
                    <span class="collection__img">
                        <span class="collection__inner-img"><img src="/img/layout/ind_abstract.jpg" alt=""></span>
                        <span class="collection__caption">Абстрактные</span>
                    </span>
                    <span class="collection__title">Abstract patterns</span>
                </a>
                <a class="collection__link-col" href="/collections/patterns">Посмотреть <span>коллекцию &rarr;</span></a>
            </div>

            <div class="collection__item js-col-item">
                <div class="collection__all" style="background-image: url('img/layout/bg-collection.png')">
                    <a class="btn" href="/collections">Все коллекции</a>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="section-title section-title--overline">Специальные предложения</div>

    <div class="spec-offer js-scrollbar">
        <div class="spec-offer__item" style="background-image: url('img/layout/bg-spec1.jpg');">
            <div class="spec-offer__caption">
                <div class="spec-offer__desc">Конструктор</div>
                <div class="spec-offer__title">Собери сам!</div>
            </div>
            <a class="btn">Создать чехол</a>
        </div>

        <div class="spec-offer__item spec-offer__item--second" style="background-image: url('img/layout/bg-spec2.jpg');">
            <div class="spec-offer__caption">
                <div class="spec-offer__desc">Powerbank</div>
                <div class="spec-offer__title">DARK FORCES</div>
            </div>
            <a class="btn">Купить</a>
        </div>

        <div class="spec-offer__item spec-offer__item--third" style="background-image: url('img/layout/bg-spec3.jpg');">
            <div class="spec-offer__caption">
                <div class="spec-offer__desc">Праздничная акция</div>
            </div>
            <a class="btn">Выбрать чехол</a>
        </div>
    </div>--}}

    @if($bestsellers->count())
        <div class="section-title section-title--overline">Бестселлеры</div>

        <div class="catalog">
            <style>
                .catalog__title {
                    overflow-y: hidden;
                    text-overflow: ellipsis;
                }
            </style>
            @foreach ($bestsellers as $bs)
                <a class="catalog__item" href="{{route('shop.product.show', $bs->id)}}">
                    <span class="catalog__img"><img src="{{$bs->mainPhoto()}}" alt=""></span>
                    <span class="catalog__title">{{$bs->name}}</span>
                    <!--<span class="catalog__price">{{$bs->price}}</span>-->
                </a>
            @endforeach
            <!--<div class="catalog__item">
                <a class="catalog__new">
                    <span class="catalog__new-title">+ Создать свой</span>
                </a>
            </div>-->

            <div class="catalog__all">
                <a class="btn" href="{{route('shop.slug', 'catalog')}}">Перейти в каталог</a>
            </div>
        </div>
    @endif
</div>

{{--<div class="main-about">
    <div class="container">
        <div class="section-title">О наших чехлах</div>
        <div class="main-about__text text text--secondary">Чехлы на айфон (iPhone) 6 / 6s — аксессуары, являющиеся не только средством для защиты от повреждений и загрязнений, но и помогающие подчеркнуть их владельцам свою индивидуальность. Многообразие вариантов позволит подобрать наиболее удобную и практичную модель. Множество чехлов на айфон 6 / 6s ждут своих владельцев для того, чтобы стать стильным дополнением образа и обеспечить мобильный телефон необходимым уровнем защиты.
        Кроме того, чехол на iPhone (айфон) 6 / 6s может быть оснащен подставкой для комфортного просмотра видео и общения в социальных сетях. Некоторые модели имеют кармашки под необходимые мелочи, вроде визиток и банковских карт. Водонепроницаемые модели защитят устройство в плохую погоду и позволят заниматься экстремальными видами спорта. Светящиеся, противоударные, спортивные, с магнитом — чехлы на айфон 6s станут помощником владельца смартфона и сделают его использование намного безопасней и комфортней. Чтобы купить чехол для iPhone 6 / 6s, важно разобраться в его свойствах.
        Накладка, книжка, бампер, кармашек — можно выбрать одну модель чехла для iPhone 6 / 6s, а можно менять их в зависимости от потребностей и настроения. Людям, которым необходим мгновенный доступ ко всем функциям смартфона, подойдет бампер на боковые стороны или накладка. Те, кто не хочет отказываться от мобильности, но волнуется за сохранность дисплея могут остановить свой выбор на книжке. Такой чехол для iPhone 6 или 6s обеспечивает всестороннюю защиту, при этом не затрудняя пользование разъемами, кнопками и дисплеем. Чехол на айфон 6s в виде кармашка удобен для переноски смартфонов в сумке или кармане. К такому чехлу на iPhone (айфон) 6 / 6s советуем приобрести защитное стекло для сохранения целостности дисплея.
        Заказать на айфон 6 / 6s чехол в интернет-магазине Applepack можно онлайн всего в один клик или по телефону.</div>
    </div>
</div>--}}
@endsection