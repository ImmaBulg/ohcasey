<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BadQuery */

$this->title = 'Update Bad Query: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bad Queries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bad-query-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
