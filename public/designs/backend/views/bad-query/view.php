<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BadQuery */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Посиковый запрос', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-query-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
            'name',
            'count',
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
