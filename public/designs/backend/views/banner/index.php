<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Банеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить банер', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function($data){

                    return "<img src='/designs". $data->path . "' alt='' class='grid-view-image'>";
                },
            ],
            'link',
            'sort',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function($data){
                    return $data->active === 1 ? '<span class="bg-success">Да</span>' : '<span class="bg-danger">Нет</span>';
                }
            ],
            [
                'attribute' => 'is_repeat',
                'format' => 'raw',
                'value' => function($data){
                    return $data->is_repeat === 1 ? '<span class="bg-success">Да</span>' : '<span class="bg-danger">Нет</span>';
                }
            ],
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
