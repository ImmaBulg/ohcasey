<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
	content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?= Html::csrfMetaTags() ?>
	<link rel="shortcut icon" href="/favicon.ico"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88014780-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-88014780-1');
    </script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript"> (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter32242774 = new Ya.Metrika({
                        id: 32242774,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true,
                        webvisor: true,
                        trackHash: true,
                        ecommerce: "dataLayer"
                    });
                } catch (e) {
                }
            });
            var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
                n.parentNode.insertBefore(s, n);
            };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";
            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks"); </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/32242774" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
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
				fbq('init', '1830520020565197'); // Insert your pixel ID here.
			fbq('track', 'PageView');
		}
	</script>
	<noscript><img height="1" width="1" style="display:none"
				   src="https://www.facebook.com/tr?id=1830520020565197&ev=PageView&noscript=1"
		/></noscript>
        <!-- DO NOT MODIFY -->
        <!-- End Facebook Pixel Code -->
</head>
<body>
<?php $this->beginBody() ?>
<div id="root">
    <div id="content">
        <header class="header">
            <div class="header-main">
                <div class="burger-wrapper">
                    <a href="#" class="header__burger" data-metrika="GAMBURGERMENU_INST">
                        <span></span><span></span><span></span>
                    </a>
                </div>
                <a data-metrika="LOGO_INST" href="/" class="logo">
                    <img src="<?=Yii::$app->urlManager->baseUrl?>/img/logo.png" alt="Ohcasey">
                </a>
                <nav class="main-nav">
                    <a href="/futbolki" class="main-nav__item" data-metrika="FUTBOLKIUPMENU_INST">ФУТБОЛКИ</a>
                    <div class="main-nav__item">
                        <span class="main-nav__item-expanded" data-metrika="ALLCASES">ВСЕ ЧЕХЛЫ</span>
                        <div class="drop-nav">
                            <a href="/collections/" class="drop-nav__link" data-metrika="AUTHORCASESUPMENU_INST">Авторские коллекции</a>
                            <a href="/glitter" class="drop-nav__link" data-metrika="GLITTERUPMENU_INST">Чехлы с блестками</a>
                            <a href="/cases" class="drop-nav__link" data-metrika="SILICONCOLORUPMENU_INST">Силиконовые одноцветные чехлы</a>
                            <!--                             <a href="/salecases" class="drop-nav__link" data-metrika="SALEUPMENU_INST">SALE!</a> -->
                        </div>
                    </div>
                    <div class="main-nav__item">
                        <span class="main-nav__item-expanded" data-metrika="CREATEBUTTION_INST">СОЗДАЙ СВОЙ ЧЕХОЛ</span>
                        <div class="drop-nav">
                            <a href="/custom" class="drop-nav__link" data-metrika="CONSTRUPMENU_INST">Online конструктор чехлов</a>
                            <a href="/designs" class="drop-nav__link" data-metrika="INSTASHOPUPMENU_INST">Instagram Shop</a>
                        </div>
                    </div>
                    <!--<a href="/kruzhki" class="main-nav__item" data-metrika="KRUZHKIUPMENU_INST">КРУЖКИ</a>-->
                    <a href="/delivery" class="main-nav__item" data-metrika="DELIVERYUPMENU_INST">ДОСТАВКА</a>
                    <a href="/contacts" class="main-nav__item" data-metrika="CONTACTSUPMENU_INST">КОНТАКТЫ</a>
                </nav>
                <a data-metrika="CART_INST" href="/cart" class="basket">
                    <svg class="basket__logo" viewBox="0 0 15.3 16">
                        <g id="svg-package">
                            <path d="M14.6,4.9h-2.8V4.2C11.8,1.9,10,0,7.7,0S3.5,1.9,3.5,4.2v0.7H0.7C0.3,4.9,0,5.2,0,5.6v9.7
            C0,15.7,0.3,16,0.7,16h13.9c0.4,0,0.7-0.3,0.7-0.7V5.6C15.3,5.2,15,4.9,14.6,4.9z M4.9,4.2c0-1.5,1.2-2.8,2.8-2.8s2.8,1.2,2.8,2.8
            v0.7H4.9V4.2z M13.9,14.6H1.4V6.3h2.1v2.1C3.5,8.7,3.8,9,4.2,9s0.7-0.3,0.7-0.7V6.3h5.6v2.1c0,0.4,0.3,0.7,0.7,0.7
            c0.4,0,0.7-0.3,0.7-0.7V6.3h2.1V14.6z"></path>
                        </g>
                    </svg>
                    <!--<div class="basket__items-count"><?= (int)Yii::$app->request->cookies->getValue('basketItemsCount', 0)?></div>-->
                    <div class="basket__items-count"><?= isset($_COOKIE["basketItemsCount"]) ? (int)$_COOKIE["basketItemsCount"] : 0;?></div>
                </a>
            </div>
        </header>

        <?= $content ?>


    </div>
    <footer class="footer page-footer">
        <div class="container footer-container">
            <nav class="footer-nav">
                <div class="footer-nav__item"><a data-metrika="CATALOGBUTTION_BUTTOM_INST" href="/cases" class="footer-nav__link">Каталог</a></div>
                <div class="footer-nav__item"><a data-metrika="CONSTRBUTTION_INST" href="/custom" class="footer-nav__link">Конструктор чехлов</a></div>
                <div class="footer-nav__item"><a href="/collections" class="footer-nav__link">Коллекции</a></div>
                <div class="footer-nav__item"><a data-metrika="DELIVERYBUTTION_INST" href="/delivery" class="footer-nav__link">Доставка и оплата</a></div>
                <div class="footer-nav__item"><a data-metrika="CONACTSBUTTION_INST" href="/contacts" class="footer-nav__link">Контакты</a></div>
            </nav>
            <div class="footer-contacts">
                <div class="footer-phone">
                    <div class="footer-phone__title">По всем вопросам звоните:</div>
                    <a href="tel:+79653969785" class="footer-phone__link" rel="nofollow">+7 (965) 396-97-85</a>
                </div>
                <div class="footer__social-links">
                    <div class="footer__social-link-wrapper">
                        <a class="footer__social-link" href="https://www.instagram.com/_ohcasey_" target="_blank" rel="nofollow">
                        <span class="social__icon">
                            <svg class="svg-icon" viewBox="0 0 16 16">
                                <g id="svg-instagram">
                                    <path id="Shape_1_" d="M8,9.2c0.6,0,1.2-0.5,1.2-1.2S8.6,6.8,8,6.8C7.4,6.8,6.8,7.4,6.8,8S7.4,9.2,8,9.2z M10.5,5.7
                                c0.4,0,0.6-0.3,0.6-0.6c0-0.4-0.3-0.6-0.6-0.6c-0.4,0-0.6,0.3-0.6,0.6C9.9,5.4,10.2,5.7,10.5,5.7z M10.3,8c0,1.3-1.1,2.3-2.3,2.3
                                S5.7,9.3,5.7,8c0-0.3,0-0.5,0.1-0.8H4.7v3.2c0,0.5,0.4,1,1,1h4.5c0.5,0,1-0.4,1-1V6.2H9.5C10,6.7,10.3,7.3,10.3,8z M8,0
                                C3.6,0,0,3.6,0,8s3.6,8,8,8s8-3.6,8-8S12.4,0,8,0z M12.3,11.2c0,0.7-0.6,1.3-1.3,1.3h-6c-0.7,0-1.3-0.6-1.3-1.3v-6
                                c0-0.7,0.6-1.3,1.3-1.3h6c0.7,0,1.3,0.6,1.3,1.3L12.3,11.2L12.3,11.2z"></path>
                                </g>
                            </svg>
                        </span>
                            <span class="social__title">Мы в Instagram</span>
                        </a>
                        <div class="counter" style="text-align: right; padding-top: 10px;">
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
        </div>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
