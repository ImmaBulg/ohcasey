<?php

namespace console\controllers;

use backend\models\CaseDesignItem;
use Yii;
use yii\console\Controller;


class PatchController extends Controller
{

    public function actionLinks()
    {
        foreach (CaseDesignItem::find()->with('caseDesign')->all() as $caseDesignItem){
            $caseDesignItem->link = $caseDesignItem->caseDesign->link;
            $caseDesignItem->save();
        }
    }

}