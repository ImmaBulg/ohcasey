<?php

namespace frontend\controllers;

use frontend\models\HashtagStat;

class HashtagStatController extends BaseController
{
    public function actionTransition()
    {
        $hashTag = \Yii::$app->request->bodyParams['hashtagName'];
        $model = HashtagStat::find()->where('name = :hashTag', [':hashTag' => $hashTag])->one();

        if(!$model){
            //Создание нового
            $model = new HashtagStat();
            $model->name = $hashTag;
            $model->click_count = 1;
        }else{
            //апдейт
            $model->click_count = $model->click_count + 1;
        }

        if(!$model->save()){
            throw new \RuntimeException('Saving error.');
        }

        return 1;
    }
}
