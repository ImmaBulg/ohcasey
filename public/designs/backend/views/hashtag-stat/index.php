<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика кликов по хештегам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hashtag-stat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'click_count',
            [
                'attribute' => 'created_at',
                'value' => function($data){
                    return date('Y-m-d H:i:s', $data->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($data){
                    return date('Y-m-d H:i:s', $data->updated_at);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>
</div>
