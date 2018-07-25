<?php
namespace frontend\controllers;

use yii\web\Controller;

class BaseController extends Controller{

    /**
     * Устанавливает title и мета-теги на странице
     * @param string $title
     * @param string|null $keywords
     * @param string|null $description
     */
    public function setMeta($title = 'Ohcasey', $keywords = null, $description = null){
        $this->view->title = $title;
        if($keywords !== null){
            $this->view->registerMetaTag([
                'name' => 'keywords',
                'content' => "$keywords",
            ]);
        }
        if($description !== null){
            $this->view->registerMetaTag([
                'name' => 'description',
                'content' => "$description",
            ]);
        }
    }

}