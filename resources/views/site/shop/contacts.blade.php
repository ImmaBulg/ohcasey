@extends('site.layouts.app')

@section('title', 'Контакты')

@section('content')
	
    <div class="mapContact" id="mapContact"></div>

    <div class="inner">
        <div class="container">
            <div class="columns">
                <div class="columns__item">
                    <div class="columns__head">Адрес</div>
                    <div class="columns__section">
                        <div><svg class="svg-icon svg-icon--metro svg-icon--metro-brown" viewBox="0 0 16 11.3"><use xlink:href="#svg-metro"></use></svg> Таганская Кольцевая (5 мин.)</div>
                        <div><svg class="svg-icon svg-icon--metro svg-icon--metro-yellow" viewBox="0 0 16 11.3"><use xlink:href="#svg-metro"></use></svg> Марксистская (3 мин.)</div>
                    </div>

                    <div class="columns__section">
                        Москва,  ул. Таганская 24 стр 1.<br>
                        Вход под козырьком «Агентство недвижимости Простор»
                    </div>
                </div>

                <div class="columns__item">
                    <div class="columns__head">График работы</div>
                    <div class="columns__section">
                        Будни: с 11 до 20<br>
                        Выходные: с 12 до 17
                    </div>
                </div>

                <div class="columns__item">
                    <div class="columns__head">Телефон</div>
                    <div class="columns__section">+7 965 396-97-85 (звонки, SMS, WhatsApp)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_PX576-6kFNaxVNSyXZY3VSJwNmtaGRs" defer></script>
@endsection