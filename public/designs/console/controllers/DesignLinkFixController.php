<?php

namespace console\controllers;

use backend\models\CaseDesignItem;
use Yii;
use yii\console\Controller;


class DesignLinkFixController extends Controller
{

    public function actionLinks()
    {
        foreach (CaseDesignItem::find()->all() as $caseDesign){
            $link = str_replace('ohcasey.ru', 'ohcasey.ru', $caseDesign->link);
            $caseDesign->link = $link;
            $caseDesign->save();
        }
    }

}