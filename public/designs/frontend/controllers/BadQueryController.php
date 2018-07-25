<?php

namespace frontend\controllers;

use frontend\models\BadQuery;
use yii\web\BadRequestHttpException;

class BadQueryController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $name = \Yii::$app->request->bodyParams["value"];
        if(!$name){
            throw new BadRequestHttpException('Bad hashtag value.');
        }

        $model = BadQuery::findByName($name);
        if(!$model){
            //Создание нового
            $model = new BadQuery();
            $model->name = $name;
            $model->count = 1;
        }else{
            //апдейт
            $model->count = $model->count + 1;
        }

        if(!$model->save()){
            throw new \RuntimeException('Saving error.');
        }

        return true;

    }

}
