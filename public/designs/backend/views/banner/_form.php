<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Banner */
/* @var $form yii\widgets\ActiveForm */
/*
$filesDir = __DIR__ . '/../../../frontend/web/files';
$serverFiles = scandir($filesDir);
$serverFiles = array_filter($serverFiles, function($file) use ($filesDir){
    $pieces = explode('.', $file);
    $ext = strtolower(end($pieces));
    return is_file($filesDir . '/' . $file) && in_array($ext, ['jpg', 'gif', 'png']);
});*/
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <div>
            <?=Html::img('/designs' . $model->path, ['class' => 'selected-image-preview'])?>
        </div>
        <?php /*<button class="btn btn-info add-image-popup"><?=$model->isNewRecord ? 'Добавить' : 'Изменить'?> изображение</button>*/?>
        <?= $form->field($model, 'path')->fileInput(['accept' => 'image/*']) ?>
    </div>
    <div class="form-group">
        <div>
            <?=Html::img('/designs' . $model->mobile_path, ['class' => 'selected-image-preview'])?>
        </div>
        <?php /*<button class="btn btn-info add-image-popup"><?=$model->isNewRecord ? 'Добавить' : 'Изменить'?> изображение</button>*/?>
        <?= $form->field($model, 'mobile_path')->fileInput(['accept' => 'image/*']) ?>
    </div>


    <?= $form->field($model, 'link')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'active')->dropDownList([0 => 'Нет', 1 => 'Да']) ?>

    <?= $form->field($model, 'is_repeat')->dropDownList([0 => 'Нет', 1 => 'Да']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
