<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pages-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'slug',
                'text:ntext',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
            ],
        ]); ?>
    </div>
</div>
