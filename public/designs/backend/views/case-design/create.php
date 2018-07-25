<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseDesign */

$this->title = 'Добавить пример дизайна';
$this->params['breadcrumbs'][] = ['label' => 'Case Designs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-design-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
