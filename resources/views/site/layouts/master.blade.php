@inject('oh', 'App\Ohcasey\Ohcasey')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="application/javascript" src="{{ url('js/jquery.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/bootstrap.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/js.cookie.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/store.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/general.js') }}"></script>
    <script type="application/javascript" src="{{ _el('js/common.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/perfect-scrollbar.jquery.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/SimpleAjaxUploader.js') }}"></script>
    <!-- Datepicker -->
    <script src="{{ url('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.ru.min.js') }}"></script>
    <script src="{{ url('js/intlTelInput.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('css/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ _el('css/app.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/font-awesome.css') }}"/>
    <link href="{{ url('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico"/>
    @yield('header')

    @if(env('APP_ENV') == 'production')
    <!-- Facebook Pixel Code -->
        <script>
            if (typeof fbq === 'undefined') {
                !function (f, b, e, v, n, t, s) {
                    if (f.fbq)return;
                    n = f.fbq = function () {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window,
                    document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
				@section('fbq-init')
					fbq('init', '1830520020565197'); // Insert your pixel ID here.
				@show
                fbq('track', 'PageView');
            }
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=1830520020565197&ev=PageView&noscript=1"
            /></noscript>
        <!-- DO NOT MODIFY -->
        <!-- End Facebook Pixel Code -->
    @endif

    <title>@yield('title')</title>
	<meta name="keywords" content="@yield('keywords')">
	<meta name="description" content="@yield('description')">
	

    @if (\BrowserDetect::isDesktop())
        {{-- LIVECHAT --}}
        <script type="text/javascript">
            window.$zopim || (function (d, s) {
                var z = $zopim = function (c) {
                    z._.push(c)
                }, $ = z.s =
                    d.createElement(s), e = d.getElementsByTagName(s)[0];
                z.set = function (o) {
                    z.set._.push(o)
                };
                z._ = [];
                z.set._ = [];
                $.async = !0;
                $.setAttribute('charset', 'utf-8');
                $.src = '//v2.zopim.com/?45xo7yJiTsz4188QKRoVSOGhq8Rq6N4j';
                z.t = +new Date;
                $.type = 'text/javascript';
                e.parentNode.insertBefore($, e)
            })(document, 'script');
        </script>
    @endif

    <script>
        window.APP_ENV = "{{ env('APP_ENV') }}";
    </script>
	@if(env('APP_ENV') == 'production')
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-TTHTM6P');</script>
	@endif
	

</head>
<body class="custom-template">
@yield('popup')
@if(env('APP_ENV') == 'production')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TTHTM6P"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
<div id="promotions">
    <div id="promotion_3x" class="promo-modal">
        <div class="promo-content 1">
            <span class="promo-close promo-action">&times;</span>
            <!--<div class="promo-info">
                <p class="promo-text">При заказе двух чехлов - третий в подарок</p>
                <a class="promo-button promo-action">ПРОМОКОД HAPPY3</a>
            </div>-->
        </div>
    </div>
</div>

<script type="text/javascript">
    window.onload = function (event) {
        if (localStorage.getItem('promo_3x_7') && parseInt(localStorage.getItem('promo_3x_7')) > (parseInt(new Date().getTime()) - 60 * 60 * 24 * 1000)) return
        var promo = document.getElementById('promotion_3x');
        promo.style.display = 'block';
        // var act = document.getElementsByClassName('promo-action')
        var act = document.getElementsByClassName('custom-template');
        for (var i = 0; i < act.length; i++) {
            act[i].onclick = function () {
                promo.style.display = 'none'
            }
        } 
        localStorage.setItem('promo_3x_7', new Date().getTime())
    }
	$(document).ready(function(){
		$("#promotion_3x").click(function(){
			$(this).hide();
		});
	});
</script>

	
@yield('body')

<div id="left">
    <div class="text-center p-15 logo_construct">
        <a href="{{ route('shop.index') }}">
            <div id="logo" class="center-block"></div>
        </a>
    </div>
	<h1 class="h1_new">Конструктор чехлов для телефонов онлайн - iPhone и Samsung Galaxy.</h1>
    <div class="left-link calc">
		<a href="{{ url('custom/delivery') }}"><span class="icon icon-calculator"></span> Калькулятор доставки</a>
	</div>
    <div class="left-link about_casey">
		<a href="{{ url('custom/about') }}"><span class="icon icon-cover"></span> О наших чехлах</a>
    </div>
    <div class="left-link in_insta">
		<a target="_blank" href="https://www.instagram.com/_ohcasey_/"><span  class="icon icon-instagram"></span> Мы в Instagram</a>
	</div>
    <div class="left-help">
        <span class="text"><span class="icon icon-phone"></span>НУЖНА ПОМОЩЬ?</span>
        <a href="tel:+79653969785">
            <div class="text phone">+7 (965) 396-97-85</div>
        </a>
    </div>
    <div class="left-icon">
        <div class="icon icon-3d"></div>
        <div>ОБЪЕМНАЯ ПЕЧАТЬ</div>
    </div>
    <div class="left-icon">
        <div class="icon icon-delivery"></div>
        <div>ОТПРАВКА 24 ЧАСА</div>
    </div>
    <div class="left-icon">
        <div class="icon icon-quality"></div>
        <div>100% ГАРАНТИЯ КАЧЕСТВА</div>
    </div>
    <div class="counter" style="position: absolute; bottom: 0; right: 5px;">
           <!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3024787", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div>
<img src="//top-fwz1.mail.ru/counter?id=3024787;js=na" style="border:0;position:absolute;left:-9999px;" alt="" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->

<!-- Top100 (Kraken) Counter -->
<script>
    (function (w, d, c) {
    (w[c] = w[c] || []).push(function() {
        var options = {
            project: 6116004,
        };
        try {
            w.top100Counter = new top100(options);
        } catch(e) { }
    });
    var n = d.getElementsByTagName("script")[0],
    s = d.createElement("script"),
    f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src =
    (d.location.protocol == "https:" ? "https:" : "http:") +
    "//st.top100.ru/top100/top100.js";

    if (w.opera == "[object Opera]") {
    d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(window, document, "_top100q");
</script>
<noscript>
  <img src="//counter.rambler.ru/top100.cnt?pid=6116004" alt="Топ-100" />
</noscript>
<!-- END Top100 (Kraken) Counter -->
<!--LiveInternet counter-->
<script type = "text/javascript">
    document.write("<a href='//www.liveinternet.ru/click' " +
        "target=_blank rel='nofollow'><img src='//counter.yadro.ru/hit?t44.6;r" +
        escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
            ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
        ";h" + escape(document.title.substring(0, 150)) + ";" + Math.random() +
        "' alt='' title='LiveInternet' " +
        "border='0' width='31' height='31'><\/a>") 
</script>
    </div>
    @yield('left')
</div>
<?php
$constructorPageUrl = isset($constructorPageUrl) ? $constructorPageUrl : url('/');
?>
<div id="content">
    <div id="header">
        <div id="mobile-menu-btn">
            <a href="{{ url('/custom/') }}"><span class="logo"></span></a>
            <label><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></label>
            <span class="menu-text">Меню</span>
        </div>
    </div>
    @yield('content')
</div>

<div id="loading" class="overlay hidden">
    <div class="loader"></div>
</div>

<script type="text/javascript">
    var URL_STORAGE = '{{ url('storage') }}';
    var URL_FONT = '{{ url('font/image') }}';
    var GEO = {!! json_encode($oh->geo()) !!};
    var BASEURL = '{{ url('/') }}';
    var isAdminEdit = {{ session('isAdminEdit', false) ? 'true' : 'false' }};
            @if(session('isAdminEdit', false))
    var editOrder = {!! session('editOrder')  !!};
    var editCase = {!! session('editCase')  !!};
    @endif
    @if(isset($source))
saveEnv({!! json_encode(isset($source) ? $source : null) !!});
    @endif
</script>

{{--@if(env('APP_ENV') == 'production')--}}
@include('_partial.metrika')
{{--@endif--}}
@stack('js')
@yield('footer')

<style>
	.custom-template .promo-content {
        /* text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -250px;
        margin-left: -250px;
        width: 500px;
        height: 500px;
        background: url('img/promotions/promo_3x-n.jpg'); */
		text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -207px;
        margin-left: -320px;
        width: 500px;
        height: 500px;
        /*background: url('img/promotions/promo_delivery.png');*/
        /* background: url('img/promotions/promo_3x-n.jpg'); */
        background: url('/img/promotions/desc.png');
    }
	
	@media (max-width: 992px){
		.custom-template .promo-content {
            /* width: 300px;
            height: 400px;
            margin-top: -200px;
            margin-left: -150px;
            background: url('img/promotions/promo_3x_mobile-n.jpg'); */
			width: 300px;
            height: 400px;
            margin-top: -151px;
            margin-left: -250px;
            /*background: url('img/promotions/promo_delivery_m.png');*/
            /* background: url('img/promotions/promo_3x_mobile-n.jpg'); */
            background: url('img/promotions/mob.png');
        }
	}
</style>
</body>
</html>
