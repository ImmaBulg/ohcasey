<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($user);

        // Создаем разрешения. просмотр админки viewAdminModule
        $viewAdminPage = $auth->createPermission('viewAdminModule');
        $viewAdminPage->description = 'Просмотр админки';
        $auth->add($viewAdminPage);

        $sendComments = $auth->createPermission('sendComments');
        $sendComments->description = 'Просмотр профиля';
        $auth->add($sendComments);

        $auth->addChild($user, $sendComments);

        $auth->addChild($admin, $user);

        $auth->addChild($admin, $viewAdminPage);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);
    }
}