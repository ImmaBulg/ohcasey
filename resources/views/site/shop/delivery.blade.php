@extends('site.layouts.app')

@section('title', 'Доставка и оплата')

@section('content')
    <div class="inner">
        <div class="container">
            <div class="headline">
                <h1 class="h1">Доставка и оплата</h1>
            </div>
            <div class="tabs">
                <ul class="tabs__btns">
                    <li class="tabs__item"><a href="#" class="tabs__link js-tab-link is-active" data-tab="1">Москва</a></li>
                    <li class="tabs__item"><a href="#" class="tabs__link js-tab-link" data-tab="2">Другие города</a></li>
                </ul>

                <div class="tabs__body">
                    <div class="tabs__content columns js-tab-content is-active" data-tab="1">
                        <?php /*<div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Самовывоз из шоурума</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    <div class="tabs__subhead">Адрес</div>
                                    <p>
                                        Москва ул. Таганская 24 стр 1. <a href="#">Как нас найти &rarr;</a><br>
                                        Пн – Пт: с 11 до 20<br>
                                        Сб – Вс: с 12 до 17
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>500 руб. с за каждый чехол</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок изготовления</div>
                                    <p>2 дня с момента оплаты</p>
                                </div>
                            </div>
                        </div> */ ?>

                        <div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Курьером на дом</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    <p>
                                        Доставка осуществляется курьерами Окейси.<br>
                                        Пн – Пт: с 10 до 19<br>
                                        Сб: с 11 до 18<br>
                                        Вс: доставки нет
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>Не требуется</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Стоимость доставки</div>
                                    <p>350 руб.</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок доставки</div>
                                    <p>2 дня на производство. Привезем на третий день после оформления заказа.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Самовывоз из пункта выдачи</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    <p>
                                        Огромное количесвто точек выдачи транспортной компании Сдэк рядом с вашим домом. При оформлении заказа в корзине вы сможете выбрать наиболее подходящий вариант.
                                        <span class="atten">Внимание! В выходные пункты выдачи не работают. При получении требуется паспорт.</span>
                                        Срок хранения заказа в пункте выдачи 14 дней
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>100%</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Стоимость доставки</div>
                                    <p>От 165 руб.</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок доставки</div>
                                    <p>2 дня на производство (с момента оплаты) +<br>
                                        2 дня доставка до выбранного пункта выдачи</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabs__content columns js-tab-content" data-tab="2">
                        <div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Самовывоз из пункта выдачи</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    Огромное количесвто точек выдачи транспортной компании Сдэк рядом с вашим домом. При оформлении заказа в корзине вы сможете выбрать наиболее подходящий вариант.
                                    <span class="atten">Внимание! В выходные пункты выдачи не работают. При получении требуется паспорт.</span>
                                    Срок хранения заказа в пункте выдачи 14 дней
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>100%</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Стоимость доставки</div>
                                    <p>От 175 руб.</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок доставки</div>
                                    <p>2 дня на производство (с момента оплаты) +<br>
                                        от 2 дней доставка(зависит от региона)</p>
                                </div>
                            </div>
                        </div>

                        <div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Курьером по России</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    <span class="atten">Внимание! В выходные курьеры доставку не осуществляют. При получении требуется паспорт.</span>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>Не требуется</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Стоимость доставки</div>
                                    <p>От 250р. в зависимости от региона.<br>
                                        Подробнее в корзине при оформлении</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок доставки</div>
                                    <p>2 дня на производство. Привезем на третий день после оформления заказа.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tabs__column columns__item js-tab-column">
                            <div class="tabs__content-head columns__head js-tab-head">Доставка Почтой России</div>

                            <div class="tabs__content-body js-tab-body">
                                <div class="columns__section">
                                    <p>
                                                <span class="atten">Высылаем 1м классом.<br>
                                                Внимание! Почта России большие молодцы. Заказы не теряют. Работают быстро и четко.</span>
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Способы оплаты</div>
                                    <p>
                                        – наличными<br>
                                        – перевод через банк онлайн<br>
                                        – оплата на сайте
                                    </p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Предоплата</div>
                                    <p>100%</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Стоимость доставки</div>
                                    <p>200 руб.</p>
                                </div>

                                <div class="columns__section">
                                    <div class="tabs__subhead">Срок доставки</div>
                                    <p>2 дня на производство (с момента оплаты) +<br>
                                        2 дня производство (после оплаты) + от 5-15 дней (зависит от региона)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection