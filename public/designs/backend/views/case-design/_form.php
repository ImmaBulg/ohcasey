<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Hashtag;
use yii\helpers\ArrayHelper;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseDesign */
/* @var $form yii\widgets\ActiveForm */


$cases = \backend\models\CaseDesign::find()->asArray()->all();
$names = array_unique(ArrayHelper::map($cases, 'id', 'name'));
asort($names);
$collections = array_unique(ArrayHelper::map($cases, 'id', 'collection'));
asort($collections);
$collectionsHtml = "";
$namesHtml = "";
foreach ($names as $name){
    $namesHtml .= "<div class='existing-fields__value'>$name</div>";
}
foreach ($collections as $collection){
    $collectionsHtml .= "<div class='existing-fields__value'>$collection</div>";
}

$filesDir = __DIR__ . '/../../../frontend/web/files';
$serverFiles = scandir($filesDir);
$serverFiles = array_filter($serverFiles, function($file) use ($filesDir){
    $pieces = explode('.', $file);
    $ext = strtolower(end($pieces));
    return is_file($filesDir . '/' . $file) && in_array($ext, ['jpg', 'gif', 'mp4', 'png']);
});

$selectedHashTags = [];
$selectedFiles = [];
if(!$model->isNewRecord){
    //Выбираем выбранные хэштеги
    $modelHashTags = $model->caseDesign2hashtags;
    foreach ($modelHashTags as $hashTag){
        $selectedHashTags[] = $hashTag->hashtag->id;
    }
    //Выбираем файлы
    foreach ($model->caseDesignItems as $caseDesignItem ){
        $selectedFiles[] = str_replace('/files/', '', $caseDesignItem->source_path);
    }
}
$serverFiles = array_filter($serverFiles, function($serverFile) use ($selectedFiles){
    return !in_array($serverFile, $selectedFiles);
});
?>

<div class="case-design-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'case-design-form',
            'autocomplete' => 'off'
        ]
    ]); ?>

    <?= $form->field($model, 'name', [
            'template' => "{label}\n{input}\n{hint}\n{error}\n<div class='existing-fields'>$namesHtml</div>",
    ])->textInput(['class' => 'form-control auto-select']) ?>

    <?= $form->field($model, 'collection', [
        'template' => "{label}\n{input}\n{hint}\n{error}\n<div class='existing-fields'>$collectionsHtml</div>",
    ])->textInput(['maxlength' => true, 'class' => 'form-control auto-select']) ?>

    <?= $form->field($model, 'stickers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'basic'
    ]) ?>

    <?php
    if($model->isNewRecord){
        $model->likes_count = 0;
    }
    ?>
    <?= $form->field($model, 'likes_count')->textInput() ?>

    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <div>
        <div class="custom-label">Хэштеги</div>
        <div class="custom-filter-wrapper">
            <div class="form-inline flex-input">
                <div class="form-group">
                    <input type="text" class="form-control hashtag-search" placeholder="Поиск по тегам">
                </div>
                <button type="submit" class="btn btn-default add-hashtag-btn disabled">Добавить новый хэштег</button>
            </div>
        </div>
        <div class="option-wrapper">
            <?php
            $hashTags = Hashtag::find()->orderBy('text')->asArray()->all();
            //Сортируем хэштеги, чтобы выбранные стояли в начале
            usort($hashTags, function ($hashTag1, $hashTag2) use ($selectedHashTags){
                $in1 = in_array($hashTag1['id'], $selectedHashTags);
                $in2 = in_array($hashTag2['id'], $selectedHashTags);
                if($in1 && !$in2){
                    return -1;
                }elseif($in2 && !$in1){
                    return 1;
                }

                return 0;
            });
            foreach ($hashTags as $hashTag):
            ?>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="hashtag[]" value="<?=$hashTag['id']?>" <?=in_array($hashTag['id'], $selectedHashTags) ? ' checked' : ''?>>
                        <span class="hashtag-text"><?=$hashTag['text']?></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="selectedFiles" value="">


    <div class="option-wrapper">
        <div class="">
            <div class="form-group ">
                <div>
                    <div class="alert alert-success upload-succes-msg" role="alert">
                        <strong>Файл успешно загружен!</strong>
                    </div>
                    <label class="btn btn-warning">
                        <span>Загрузить файл</span>
                        <input type="file" name="fast-load-file" accept="video/mp4, video/x-m4v,video/*, image/*">
                    </label>
                    <a href="#" class="btn-success btn disabled fast-download-btn">Загрузить</a>
                </div>
                <label class="control-label">Файлы выбранные</label>
                <ul class="connectedSortable sortable selected-list">
                    <?php foreach ($model->caseDesignItems as $caseDesignItem):
                        $selectedFile = str_replace('/files/', '', $caseDesignItem->source_path);
                        if(isVideo($selectedFile)):
                            ?>
                            <li class="sortable-item sortable-item-flex">
                                <div class="sortable-item__main">
                                    <img src="/designs/img/camera.png" alt=""> <span class="selected-list__file-name"><?=$selectedFile?></span>
                                </div>
                                <div class="sortable-item__input">
                                    <label class="control-label">
                                        Ссылка
                                        <input type="text" value="<?=$caseDesignItem->link?>" maxlength="255" class="form-control">
                                    </label>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-danger delete-case-file">Удалить</a>
                                </div>
                            </li>
                        <?php else:?>
                            <li class="sortable-item sortable-item-flex">
                                <div class="sortable-item__main">
                                    <img src="/designs/files/<?=$selectedFile?>" alt=""> <span class="selected-list__file-name"><?=$selectedFile?></span>
                                </div>
                                <div class="sortable-item__input">
                                    <label class="control-label">
                                        Ссылка
                                        <input type="text" value="<?=$caseDesignItem->link?>" maxlength="255" class="form-control">
                                    </label>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-danger delete-case-file">Удалить</a>
                                </div>
                            </li>
                        <?php endif;?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="modal fade bs-example-modal-sm" id="small-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header modal-message">
                Такой хэштег уже существует
            </div>
        </div>
    </div>
</div>

<div class="hashtag-description-popup">
    <div class="container">
        <div class="form-group">
            <label class="control-label" for="add-hashtag-description">Описание хештега</label>
            <textarea id="add-hashtag-description" cols="30" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-success" id="add-hashtag-btn">Добавить</button>
            <button class="btn btn-danger" id="add-hashtag-cancel">Отмена</button>
        </div>
    </div>
</div>

<?php
function isVideo($videoName){
    $pieces = explode('.', $videoName);
    $ext = strtolower(end($pieces));
    return in_array($ext, ['mp4']);
}
?>