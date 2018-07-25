<?php

/*    $this->title = $page->title;
    $this->registerMetaTag(['name' => 'keywords', 'content' => $page->keywords]);
    $this->registerMetaTag(['name' => 'description', 'content' => $page->description ]);*/

?>


<div class="small-container">
    <input hidden type="text" class="keywords" value="<?= $page->keywords ?>">
    <input hidden type="text" class="description" value="<?= $page->description ?>">
    <input hidden type="text" class="title" value="<?= $page->title ?>">
    <div class="section-preview">
        <<?= empty($hashTagName) ? 'h1' : 'div'?> class="section-title">
        Instagram Shop
        </<?= empty($hashTagName) ? 'h1' : 'div'?>>
    <h4 class="section-subtitle">
        <?= !empty($hashTag) ? $hashTag->h1 : $page->text ?>
    </h4>
    <div class="section-subtitle">
        <?= !empty($hashTag) ? $hashTag->topText : '' ?>
    </div>
    </div>

    <div class="grid">
        <?php if(!empty($hashTagName)): ?>
        <div class="grid__selected-hashtag">
            <a href="<?=!empty(Yii::$app->urlManager->baseUrl) ? Yii::$app->urlManager->baseUrl : '/'?>" class="clear-hashtag">
                <span>&lsaquo;</span> Назад
            </a>
            <h1 class="grid__selected-hashtag-value"><?=$hashTagName?></h1>
        </div>
        <?php endif; ?>
        <header class="grid-header">
            <div class="grid-styles">
                <a href="#" class="active grid-style" data-grid-style="grid" data-analytic="view_grid" data-metrika="GRIDBUTTION">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/grid.svg" type="image/svg+xml"></object>
                    </span>
                </a>
                <a href="#" class="grid-style" data-grid-style="list" data-analytic="view_lenta" data-metrika="FEEDBUTTION">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/list.svg" type="image/svg+xml"></object>
                    </span>
                </a>
            </div>
            <div class="grid-filters">
                <a href="#" class="grid-filter active" data-filter="timeStamp" data-analytic="filter_new_main" data-metrika="NEWBUTTION_INST">Новые</a>
                <a href="#" class="grid-filter" data-filter="likesCount" data-analytic="filter_top_main" data-metrika="POPULARBUTTION_INST">Популярные</a>
            </div>
            <div class="grid-search">
                <div class="grid-search-links">
                <a href="#" class="open-search" data-analytic="search" data-metrika="SEARCHBUTTION_INST">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/search.svg" type="image/svg+xml"></object>
                    </span>
                </a>
                <a href="#" class="close-search">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/close.svg" type="image/svg+xml"></object>
                    </span>
                </a>
                </div>
                <div class="search-field">
                    <form action="" id="search-by-tag-form">
                        <input type="text" class="search-field__input" placeholder="Поиск по тегам">
                    </form>
                    <div class="search-results">
                    </div>
                </div>
            </div>
        </header>
        <div class="hashtag-description">

        </div>
        <div class="grid-body"></div>
    </div>

    <div class="textBottom">
        <?= !empty($hashTag) ? $hashTag->bottomText : '' ?>
    </div>

</div>


<div class="design-card">
    <div class="design-card-content">
        <header class="design-card-header">
            <a href="#" class="go-back-link" data-analytic="back_card" data-metrika="BACKBUTTION_INST" >
                <span>&lsaquo;</span> Назад
            </a>
            <a href="#" class="like-btn" data-analytic="like_card" data-metrika="LIKEBUTTION">
                <span class="icon icon-heart"></span>
                <div class="like-count">0</div>
            </a>
        </header>
        <div class="design-card-body">
            <div class="design-card-badge"></div>
            <div class="design-card-main"></div>            
            <div class="design-card-control-row">
                <a data-metrika="WANTITBUTTION_INST" href="#" class="filled-btn design-wish-link" target="_blank" data-analytic="want_it_card">
                    Хочу такой
                </a>
            </div>
            <div class="design-card-control-row">
                <a href="#" class="design-card__contact-link" data-analytic="contact_us" data-metrika="WRITEUS_INST">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/feedback.svg" type="image/svg+xml"></object>
                    </span>
                    Связаться с нами
                </a>
            </div>
            <div class="design-card-hashtags"></div>
			<div class="design-card-control-row design-description"></div>
        </div>
    </div>
    <div class="design-card-related">
        <h3 class="design-card-related__header">Похожие</h3>
        <div class="design-card-related-grid"></div>
    </div>
    <footer class="footer">
        <div class="container footer-container">
            <nav class="footer-nav">
                <div class="footer-nav__item"><a href="/cases" class="footer-nav__link">Каталог</a></div>
                <div class="footer-nav__item"><a href="/custom" class="footer-nav__link">Конструктор чехлов</a></div>
                <div class="footer-nav__item"><a href="/collections" class="footer-nav__link">Коллекции</a></div>
                <div class="footer-nav__item"><a href="/delivery" class="footer-nav__link">Доставка и оплата</a></div>
                <div class="footer-nav__item"><a href="/contacts" class="footer-nav__link">Контакты</a></div>
            </nav>
            <div class="footer-contacts">
                <div class="footer-phone">
                    <div class="footer-phone__title">По всем вопросам звоните:</div>
                    <a href="tel:+79653969785" class="footer-phone__link" data-metrika="CALLUSBUTTION_INST" >+7 (965) 396-97-85</a>
                </div>
                <div class="footer__social-links">
                    <div class="footer__social-link-wrapper">
                        <a data-metrika="OURINSTAGRAM_INST" class="footer__social-link" href="https://www.instagram.com/_ohcasey_" target="_blank">
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
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<div class="feedback-popup">
    <div class="feedback-popup__inner">
        <a href="#" class="feedback-popup-close">
            <span class="icon-wrapper">
                <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/close.svg" type="image/svg+xml"></object>
            </span>
        </a>
        <h3 class="feedback-popup__title">
            Какой мессенджер удобнее для вас
        </h3>
        <div class="feedback-types">
            <a href="https://api.whatsapp.com/send?phone=79653969785" target="_blank" class="feedback-type" data-analytic="contact_us_whatsup" data-metrika="WHATSUPBUTTION_INST" rel="nofollow">
                <div class="feedback-type-icon">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/whatsapp.svg" type="image/svg+xml"></object>
                    </span>
                </div>
                <div class="feedback-type__name">WhatsApp</div>
            </a>
            <a href="https://t.me/Ohcasey" class="feedback-type" target="_blank" data-analytic="contact_us_telegram" data-metrika="TELEGRAMBUTTION" rel="nofollow">
                <div class="feedback-type-icon">
                    <span class="icon-wrapper">
                        <object data="<?=Yii::$app->urlManager->baseUrl?>/svg/telegram.svg" type="image/svg+xml"></object>
                    </span>
                </div>
                <div class="feedback-type__name">Telegram</div>
            </a>
        </div>
    </div>

</div>

<script>
    window.designs = <?=$designs?>;
    window.banners = JSON.parse('<?=$banners?>');
    window.hashTags = <?=$hashTags?>;
    window.hashTagName = '<?=$hashTagName?>';
</script>