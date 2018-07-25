<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Banner */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Банер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => Html::img($model->path, ['class' => 'grid-view-image']),
            ],
            [
                'attribute' => 'mobile_path',
                'format' => 'raw',
                'value' => Html::img($model->mobile_path, ['class' => 'grid-view-image']),
            ],
            'link',
            'sort',
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i:s', $model->created_at)
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s', $model->updated_at)
            ],
        ],
    ]) ?>

</div>
