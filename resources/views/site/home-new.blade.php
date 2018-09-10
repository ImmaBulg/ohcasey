@extends('site.layouts.app')

<?php
    $title = 'Ohcasey';
    $description = 'Интересные и классные модели для iPhone и Samsung. Интернет магазин  конструктором - более 1000 дизайнов и расцветок. ✔Создай свой уникальный чехол! С Ohcasey все подружки будут завидовать! Доставка по всей России. ✆ +7 (965) 396-97-85';
    $image_alt = '';
    $keywords = explode(', ', $page->keywords);

    if (count($keywords) > 3) {
        $title = $title . ' - ' . $keywords[2] . ' - ' . $keywords[count($keywords) - 1];

        $description = mb_substr(mb_strtoupper($keywords[3]), 0, 1) . mb_substr($keywords[3], 1) . ': интересные и классные модели для iPhone и Samsung. Интернет магазин: ';
        $description = $description . $keywords[0] . ' с конструктором - более 1000 дизайнов и расцветок. ✔Создай свой уникальный чехол! С Ohcasey все подружки будут завидовать! Доставка по всей России. ✆ +7 (965) 396-97-85'; 
        $image_alt = $keywords[1];
    }
?>

@section('title', $title)
@section('keywords', $page->keywords)
@section('description', $description)
{{-- @section('description', 'Красивые чехлы для телефонов. Интересные и классные модели для iPhone и Samsung. Интернет магазин чехлов с конструктором - более 1000 дизайнов и расцветок. ✔Создай свой уникальный чехол! С Ohcasey все подружки будут завидовать! Доставка по всей России. ✆ +7 (965) 396-97-85') --}}

@section('content')
<div class="container main_page">
    <h1>{{$page->h1}}</h1>
    
    <p class="before-main-banner">Ohcasey очень тщательно продумывает и подбирает иллюстрации для столь трепетно создаваемых нами чехлов, сотрудничая с лучшими и известными художниками России.<br>
        Саша Спринг, Татьяна Шашкина, Полина Ишханова, Kseniya Rain и многие другие не менее талантливые авторы принимают участие в создании поистине красивых и оригинальных коллекций чехлов.</p>
    
    <div class="main-banner">
        <a href="/collections" class="mainpage-promo">
            <img src="/img/layout/main-banner.jpg" class="main-banner-new" alt="{{$image_alt}} main-banner.jpg" title="main-banner.jpg заказать">
            <div class="main-banner-text">
                <div class="main-banner-title-text">
                    <span>АВТОРСКИЕ</span>
                    <br>
                    <span>КОЛЛЕКЦИИ</span>
                </div>
                <div class="main-banner-description-text">
                    чехлы для IPhone
                </div>
                <div class="main-banner-buy-button banner-button">
                    <p>купить</p>
                </div>
            </div>
        </a>
    </div>

    <div class="after-main-banner">
        <h2>Купить чехол для телефона Ohcasey - подчеркнуть свой имидж</h2>
        <p>
        Наши красивые и уникальные дизайны чехлов всегда привлекут внимание и восхищение окружающих. Ведь телефон - это значимый инструмент общения, творчества, обмена информацией, с которым мы чаще всего не готовы расстаться даже на минуту. Яркий и оригинальный рисунок не только подчеркнет вашу особенность, но и доставит удовольствие прежде всего вам.
        </p>
    </div>
    
    <div class="collection">
        <div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link" href="/custom">
                <span class="collection__img">
                    <span class="collection__inner-img">
                        <div class="labels-banner">
                            <img src="/img/layout/labels-banner.jpg" alt="{{$image_alt}} labels-banner.jpg" title="lables-banner.jpg заказать">
                            <div class="labels-banner-text">
                                <div class="labels-banner-title-text">
                                    <span>ЧЕХЛЫ</span>
                                    <br>
                                    <span>С НАДПИСЯМИ</span>
                                </div>
                                <div class="labels-banner-button banner-button">
                                    <p>СОЗДАЙ СВОЙ</p>
                                </div>
                            </div>
                        </div>
                    </span>
                </span>
            </a>
        </div>
        <?php /*<div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link">
                <span class="collection__img">
                    <span class="collection__inner-img"><img src="/img/layout/three-banner.jpg" alt=""></span>
                </span>
            </a>
        </div> */ ?>
        <div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link" href="/delivery">
                <span class="collection__img">
                    <span class="collection__inner-img">
                        <div class="delivery-banner">
                            <img src="/img/layout/delivery-banner.jpg" alt="{{$image_alt}} delivery-banner.jpg" title="delivery-banner.jpg заказать">
                            <div class="delivery-banner-text">
                                <div class="delivery-banner-button banner-button">
                                    <p>ПРО ДОСТАВКУ</p>
                                </div>
                                <div class="delivery-banner-description-text">
                                    <span>ВЫБЕРИ СВОЙ ГОРОД</span>
                                </div>
                            </div>
                        </div>
                    </span>
                </span>
            </a>
        </div>
{{--         <div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link" href="/cases/silicon-cases">
                <span class="collection__img">
                    <span class="collection__inner-img"><img src="/img/layout/silicon-banner.jpg" alt="{{$image_alt}} silicon-banner.jpg" title="silicon-banner.jpg заказать"></span>
                </span>
            </a>
        </div> --}}
        <div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link" href="https://www.instagram.com/_ohcasey_" target="_blank">
                <span class="collection__img">
                    <span class="collection__inner-img">
                        <div class="collection-banner">
                            <img src="/img/layout/instagram-banner.jpg" alt="{{$image_alt}} instagram-banner.jpg" title="instagram-banner.jpg заказать">
                        </div>
                    </span>
                </span>
            </a>
        </div>
        {{--<div class="collection__item js-col-item mainpage-promo">
            <a class="collection__link" href="/accessories/chargers">
                <span class="collection__img">
                    <span class="collection__inner-img"><img src="/img/layout/chargers-banner.jpg" alt=""></span>
                </span>
            </a>
        </div>--}}
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

    <div class="before-popular-item">
        <h3>Интернет магазин чехлов для телефонов Ohcasey предоставляет:</h3> 
        <p>
            <span>Авторские коллекции, отсортированные по тематикам для вашего удобства.</span><br>
            <span>Индивидуальный дизайн. В нашем онлайн конструкторе вы можете добавить к понравившемуся дизайну любые надписи, смайлы и эмодзи.</span><br>
            <span>Дизайн на заказ. <a href="/contacts/">Вы можете связаться с нашим менеджером</a> и описать все ваши пожелания. Мы можем нарисовать вам индивидуальную иллюстрацию или обработать вашу фотографию для печати на чехле.</span>
        </p>
        <h3>Защита мобильных телефонов красивыми и очень красивыми чехлами.</h3>
        <p>
            Красота спасет не только мир, но и ваш телефон. Мы производим наши чехлы по новейшим стандартам качества, уделяя особое внимание защите всех важных частей вашего телефона. Экран защищен специальным бортиком, кнопки и динамик спрятаны под ударопрочным материалом. Таким образом, защита вашего смартфона будет обладать хорошими противоударными и влагонепроницаемыми характеристиками.
        </p>
    </div>

    @if($bestsellers->count())
        <div class="section-title section-title--overline"><h3>Популярные чехлы для смартфонов</h3></div>

        <div class="catalog async-catalog">
            @foreach ($bestsellers as $bs)
                <a class="catalog__item" href="{{route('shop.product.show', [$bs->id, 'sort' => '', 'device' => 'iphonex', 'case' => 'silicone', 'color' => 1])}}">
                    <span class="catalog__img"><img src="{{$bs->mainPhoto()}}" alt="{{$image_alt}} {{$bs->name}}" title="{{$bs->name}} заказать"></span>
                    <span class="catalog__title">{{$bs->name}}</span>
                </a>
            @endforeach
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

