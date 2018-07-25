<?php
namespace frontend\controllers;

use backend\models\search\CaseDesignSearch;
use frontend\models\Banner;
use frontend\models\CaseDesign;
use frontend\models\Hashtag;
use frontend\models\Page;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Main controller
 */
class MainController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $designs = CaseDesign::find()
            ->orderBy('likes_count')
            ->with(['caseDesign2hashtags.hashtag', 'caseDesignItems'])
            ->asArray()
            ->all();

        $resDesigns = [];
        $allHashTags = [];
        foreach ($designs as $design){
            $hashTags = ['#' . str_replace(['-', ' '], '_', mb_strtolower($design['name'])), '#' . str_replace(['-', ' '], '_', mb_strtolower($design['collection']))];
            $slides = [];
            foreach ($design['caseDesign2hashtags'] as $hashtag){
                $hashTags[] = $hashtag['hashtag']['text'];
                $allHashTags[$hashtag['hashtag']['text']] = $hashtag['hashtag']['description'];
            }
            foreach ($design['caseDesignItems'] as $item){
                $slides[] = [
                    'type' => $item['type'],
                    'path' => $item['path'],
                    'link' => $item['link'],
                ];
            }
            if(empty($slides)) continue;
            $page = Page::find()->where('id = 2')->one();
            $resDesigns[$design['id']] = [
                'id' => $design['id'],
                'stickers' => $design['stickers'],
                'name' => $design['name'],
                'collection' => $design['collection'],
                'description' => $design['description'],
                'link' => $design['link'],
                'h1' => $design['h1'],
                'meta_title' => $page->title,
                'meta_keywords' => $page->keywords,
                'meta_description' => $page->description,
                'likesCount' => (int)$design['likes_count'],
                'timeStamp' => $design['created_at'] * 1000, //Для js переводим в милисекунды
                'hashTags' => array_values(array_unique($hashTags)),
                'slides' => $slides,
                'liked' => false,
            ];
        }
        $designs = json_encode($resDesigns);
        $hashTags = json_encode($allHashTags);

        //Выбираем банеры
        $banners = Banner::find()
            ->where('active = 1')
            ->orderBy('sort')
            ->asArray()
            ->all();

        $banners = json_encode($banners);

        /*Устанавливаем сразу мета тэги, если пользователь  просматривает товар*/
        $path = Yii::$app->request->getPathInfo();
        $this->setMeta(); //Устанавливаем мета тэги по умолчанию
        $hashTagName = '';
        $page = null;
        $hashTag = null;
        if(!empty($path)){
           $caseDesign = CaseDesign::findOne($path);
            if($caseDesign){
                $this->setMeta($caseDesign->meta_title, $caseDesign->meta_keywords, $caseDesign->meta_description);
            }else{
                $hashTag = Hashtag::find()->where('text = :text', [':text' => '#' . mb_strtolower($path)])->one();
                if($hashTag){
                    $this->setMeta($hashTag->meta_title, $hashTag->meta_keywords, $hashTag->meta_description);
                    $hashTagName = '#' . mb_strtolower($path);
                }else{
                    $query = "SELECT * FROM ". CaseDesignSearch::tableName() ." where LOWER(`name`) LIKE '%". mb_strtolower(str_replace(['_'], '%', $path)) ."%' ";
                    $caseDesign = CaseDesign::findBySql($query)->one();
                    if($caseDesign){
                        $this->setMeta($caseDesign->meta_title, $caseDesign->meta_keywords, $caseDesign->meta_description);
                        $hashTagName = '#' . mb_strtolower($path);
                    }else{
                        $query = "SELECT * FROM ". CaseDesignSearch::tableName() ." where LOWER(`collection`) LIKE '%". mb_strtolower(str_replace(['_'], '%', $path)) ."%' ";
                        $caseDesign = CaseDesign::findBySql($query)->one();
                        if($caseDesign){
                            $this->setMeta($caseDesign->meta_title, $caseDesign->meta_keywords, $caseDesign->meta_description);
                            $hashTagName = '#' . mb_strtolower($path);
                        }
                    }
                }
            }
            $page = Page::find()->where('id = 2')->one();
        }
        else
        {
            $page = Page::find()->where('id = 1')->one();
            $this->setMeta($page->title, $page->keywords, $page->description);
        }

        return $this->render('index', compact('designs', 'banners', 'hashTags', 'hashTagName', 'hashTag', 'page'));
    }

    public function actionLikeDesign($id){
        $caseDesign = CaseDesign::findOne($id);
        if(!$caseDesign){
            throw new BadRequestHttpException('Неправильный id дизайна');
        }
        $caseDesign->likes_count++;

        return $caseDesign->save();
    }

}
