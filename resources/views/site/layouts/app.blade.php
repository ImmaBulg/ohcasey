<!DOCTYPE html>
<html lang="ru">
<head>
    <title>@yield('title')</title>
    <!-- <base href="/" > -->
    <meta charset="utf-8"/>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">

    <link rel="apple-touch-icon" href="/apple-touch-icon-iphone.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-ipad.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-iphone-retina.png"/>
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-ipad-retina.png"/>
    <!--<link rel="shortcut icon" type="image/png" href="/favicon.png"/>-->
    <link rel="shortcut icon" href="/favicon.ico"/>

    <link href="https://fonts.googleapis.com/css?family=Tinos:400,400i,700,700i&amp;subset=cyrillic-ext"
          rel="stylesheet">

    @if(isset($GoogleDataLayer))
        <script>
            (function () {
                window.dataLayer = window.dataLayer || []
                dataLayer.push({!! json_encode($GoogleDataLayer) !!});
            })();
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

    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}" media="all"/>
    <link rel="stylesheet" href="{{url('css/font-awesome.min.css')}}" media="all"/>
    <link rel="stylesheet" href="{{url('css/jquery.mCustomScrollbar.css')}}" media="all"/>
    <link rel="stylesheet" href="{{url('css/slick.css')}}" media="all"/>
    <link rel="stylesheet" href="{{url('css/select2.min.css')}}" media="all"/>
    <link rel="stylesheet" href="{{url('css/styles.css')}}" media="all"/>
    <link href="{{ url('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script src="{{ _el('js/metrikaGoals.js') }}"></script>
    @stack('css')
    @yield('header')

    
	
	<script>
		window.dataLayer = window.dataLayer || [];
	</script>
</head>
<body>
	
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
	<noscript><img alt="" height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1830520020565197&ev=PageView&noscript=1"/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- End Facebook Pixel Code -->
@endif

@if(env('APP_ENV') == 'production')
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TTHTM6P"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
<svg style="display: none;">
    <defs>
        <g id="svg-package">
            <path d="M14.6,4.9h-2.8V4.2C11.8,1.9,10,0,7.7,0S3.5,1.9,3.5,4.2v0.7H0.7C0.3,4.9,0,5.2,0,5.6v9.7
        C0,15.7,0.3,16,0.7,16h13.9c0.4,0,0.7-0.3,0.7-0.7V5.6C15.3,5.2,15,4.9,14.6,4.9z M4.9,4.2c0-1.5,1.2-2.8,2.8-2.8s2.8,1.2,2.8,2.8
        v0.7H4.9V4.2z M13.9,14.6H1.4V6.3h2.1v2.1C3.5,8.7,3.8,9,4.2,9s0.7-0.3,0.7-0.7V6.3h5.6v2.1c0,0.4,0.3,0.7,0.7,0.7
        c0.4,0,0.7-0.3,0.7-0.7V6.3h2.1V14.6z"/>
        </g>

        <g id="svg-instagram">
            <path id="Shape_1_" d="M8,9.2c0.6,0,1.2-0.5,1.2-1.2S8.6,6.8,8,6.8C7.4,6.8,6.8,7.4,6.8,8S7.4,9.2,8,9.2z M10.5,5.7
        c0.4,0,0.6-0.3,0.6-0.6c0-0.4-0.3-0.6-0.6-0.6c-0.4,0-0.6,0.3-0.6,0.6C9.9,5.4,10.2,5.7,10.5,5.7z M10.3,8c0,1.3-1.1,2.3-2.3,2.3
        S5.7,9.3,5.7,8c0-0.3,0-0.5,0.1-0.8H4.7v3.2c0,0.5,0.4,1,1,1h4.5c0.5,0,1-0.4,1-1V6.2H9.5C10,6.7,10.3,7.3,10.3,8z M8,0
        C3.6,0,0,3.6,0,8s3.6,8,8,8s8-3.6,8-8S12.4,0,8,0z M12.3,11.2c0,0.7-0.6,1.3-1.3,1.3h-6c-0.7,0-1.3-0.6-1.3-1.3v-6
        c0-0.7,0.6-1.3,1.3-1.3h6c0.7,0,1.3,0.6,1.3,1.3L12.3,11.2L12.3,11.2z"/>
        </g>
        <g id="svg-metro">
            <path d="M11.3,0L8,6L4.7,0L1,9.8H0v1.3h5.2V9.8H4.3l1.1-3.1L8,11.3l2.6-4.5l1.1,3.1h-0.9v1.3H16V9.8h-1L11.3,0z"/>
        </g>

        <g id="svg-pin">
            <path d="M5.6,16c0,0,5.6-7.3,5.6-10.4C11.2,2.5,8.7,0,5.6,0S0,2.5,0,5.6C0,8.7,5.6,16,5.6,16z"/>
            <ellipse class="st0" cx="5.6" cy="5.3" rx="1.8" ry="1.8"/>
        </g>
        <!-- <svg class="svg-icon" viewBox="0 0 15.3 16"><use xlink:href="#svg-package"></use></svg> -->
        <!-- <svg class="svg-icon" viewBox="0 0 16 16"><use xlink:href="#svg-instagram"></use></svg> -->
    </defs>
</svg>
<div class="wrapper">
    @if ($settings['action_display'])
        <div class="top-line js-top-line is-hidden">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="top-line__text">{!! $settings['action_text'] !!}</div>
                        <a class="top-line__close js-close-top-line"></a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="hidden-lg hidden-md col-sm-2 col-xs-3">
                    <a class="header__burger js-burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-8 col-xs-6" id="logo">
                    <a href="{{\URL::current() != route('shop.index') ? route('shop.index') : '#logo'}}"
                       class="logo"><img src="/img/layout/logo.png" alt="ohcasey"></a>
                </div>

                <div class="col-lg-8 col-md-8 nav js-nav">
                    @include('site.menu.top')
                    <div class="phone">
                        <div class="phone__title">По всем вопросам звоните:</div>
                        <a class="phone__num" href="tel:+7(965)3969785">+7 (965) 396-97-85</a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                    <div class="cart">
                        <a class="cart__link" href="{{url('/cart')}}">
                            <span class="cart__icon"><svg class="svg-icon" viewBox="0 0 15.3 16"><use
                                            xlink:href="#svg-package"></use></svg></span>
                            <span id="cart__count"
                                  class="cart__counter js-cart-counter">{{($cartHelper->exists()? ($cartHelper->get()->summary->cnt ?: 0) : 0)}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @yield('content')
</div>

<footer class="footer js-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                <ul class="foot-nav" style="display:block; {{--display:flex--}}">
                    <li class="foot-nav__item">
                        <a href="{{route('shop.slug', 'cases')}}" class="foot-nav__link">Каталог</a>
                    </li>
                    <li class="foot-nav__item">
                        <a href="/custom" class="foot-nav__link">Конструктор чехлов</a>
                    </li>

                    <li class="foot-nav__item">
                        <a href="{{route('shop.slug', 'collections')}}" class="foot-nav__link">Коллекции</a>
                    </li>
                    {{--
                    <li class="foot-nav__item">
                        <a href="#" class="foot-nav__link">Оптовые заказы</a>
                    </li>
                    <li class="foot-nav__item">
                        <a href="#" class="foot-nav__link">О наших чехлах</a>
                    </li>--}}
                    <li class="foot-nav__item">
                        <a href="{{route('shop.slug', 'delivery')}}" class="foot-nav__link">Доставка и оплата</a>
                    </li>
                    <li class="foot-nav__item">
                        <a href="{{route('shop.slug', 'contacts')}}" class="foot-nav__link">Контакты</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                <div class="phone">
                    <div class="phone__title">По всем вопросам звоните:</div>
                    <a class="phone__num" href="tel:+7(965)3969785">+7 (965) 396-97-85</a>
                </div>
                <a class="social social--instagram" href="https://www.instagram.com/_ohcasey_" target="_blank">
                    <span class="social__icon"><svg class="svg-icon" viewBox="0 0 16 16"><use
                                    xlink:href="#svg-instagram"></use></svg></span>
                    <span class="social__title">Мы в Instagram</span>
                </a>
                <div class="counter" style="position: absolute; right: 33.5%; padding-top:10px;">
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
            </div>
        </div>
    </div>
</footer>

{{--    @if(env('APP_ENV') == 'production')--}}
@include('_partial.metrika')
{{--@endif--}}
<!-- Footer Scripts -->
<script src="{{url('js/jquery.min.js')}}" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<script type="application/javascript" src="{{ url('js/bootstrap.js') }}"></script>
<script src="{{url('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{url('js/slick.min.js')}}"></script>
<script src="{{url('js/select2.full.min.js')}}"></script>
<script src="{{url('js/jquery.sticky.js')}}"></script>
<script src="{{url('js/select2.ru.js')}}"></script>
<script src="{{url('js/jquery.mask.min.js')}}"></script>
<script src="{{url('js/moment.min.js')}}"></script>
<script src="{{ url('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('js/bootstrap-datepicker.ru.min.js') }}"></script>
<!-- Custom js scripts -->
<script src="{{url('js/main.js')}}"></script>
@stack('js')
@yield('scripts')
</body>
</html>
