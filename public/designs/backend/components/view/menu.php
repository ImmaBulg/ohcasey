<?php
use mihaildev\elfinder\InputFile;
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse"><span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/admin/">Ohcasey</a></div>
        <ul class="navbar-nav navbar-right nav" id="w0-collapse">

            <li>
                <a href="<?=\yii\helpers\Url::to(['case-design/index'])?>">Дизайны</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['hashtag/index'])?>">Хэштеги</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['banner/index'])?>">Банеры</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['bad-query/index'])?>">Поисковые запросы</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['hashtag-stat/index'])?>">Статистика хэштегов</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['page/index'])?>">Метатеги</a>
            </li>
        </ul>
    </div>
</nav>