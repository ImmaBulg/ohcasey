<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\BadQuery */

$this->title = 'Create Bad Query';
$this->params['breadcrumbs'][] = ['label' => 'Bad Queries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-query-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
