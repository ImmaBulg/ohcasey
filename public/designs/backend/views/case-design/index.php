<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\search\CaseDesignSearch */

$this->title = 'Примеры дизайна чехла';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-design-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пример дизайна', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="table-responsive">
    <?= GridView::widget([
        'id' => 'case-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => [
            'class' => 'sortable-row'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'Превью',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->caseDesignItems)){
                        $designItem  = $data->caseDesignItems[0];
                        if($designItem->type == 'video'){
                            return 'video';
                        }else{
                            return Html::img('/designs' . $designItem->path, ['class' => 'grid-view-image']);
                        }
                    }
                    return '';
                },
            ],
            [
                //'class' => \yii\grid\DataColumn::className(),
                'attribute' => 'name',
                'format' => 'text',
            ],
            'collection',
            'stickers',
            'likes_count',
            /*'link',*/
            [
                'attribute' => 'created_at',
                'value' => function($data){
                    return date('Y-m-d H:i:s', $data->created_at);
                },
                'filter' => false
            ],
            /*[
                'attribute' => 'updated_at',
                'value' => function($data){
                    return date('Y-m-d H:i:s', $data->updated_at);
                }
            ],*/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
