<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Hashtag */

$this->title = 'Добавить хэштег';
$this->params['breadcrumbs'][] = ['label' => 'Hashtags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hashtag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
