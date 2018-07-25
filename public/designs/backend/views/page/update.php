<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Hashtag */

$this->title = 'Изменить текст страницы: ' . $model->slug;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="hashtag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
