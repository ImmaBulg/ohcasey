<?php
namespace backend\components;

use \yii\base\Widget;
class MenuWidget extends Widget
{
    public function run()
    {
        return $this->getMenuHtml();
    }

    protected function getMenuHtml(){
        ob_start();
        include __DIR__ . '/view/menu.php';
        return ob_get_clean();
    }
}