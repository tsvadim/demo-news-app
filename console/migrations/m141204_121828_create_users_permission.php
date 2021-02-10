<?php

use yii\db\Migration;

class m141204_121828_create_users_permission extends Migration
{
    public function up()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;

        // add "index" permission
        $usersIndex = $auth->createPermission('users.index');
        $usersIndex->description = 'Просмотр списка пользователей';
        $auth->add($usersIndex);

        // add "create" permission
        $usersCreate = $auth->createPermission('users.create');
        $usersCreate->description = 'Добавить пользователя';
        $auth->add($usersCreate);

        // add "update" permission
        $usersUpdate = $auth->createPermission('users.update');
        $usersUpdate->description = 'Редактировать пользователя';
        $auth->add($usersUpdate);

        // add "delete" permission
        $usersDelete = $auth->createPermission('users.delete');
        $usersDelete->description = 'Удалить пользователя';
        $auth->add($usersDelete);


        // add role from permission
        $usersManager = $auth->createPermission('usersManager');
        $usersManager->description = 'Управление пользователями';
        $auth->add($usersManager);
        $auth->addChild($usersManager, $usersIndex);
        $auth->addChild($usersManager, $usersCreate);
        $auth->addChild($usersManager, $usersUpdate);
        $auth->addChild($usersManager, $usersDelete);

        //add role to admin
        $admin = $auth->getRole(\common\models\User::ROLE_IS_ADMIN);
        $auth->addChild($admin, $usersManager);
    }

    public function down()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('users.index'));
        $auth->remove($auth->getPermission('users.create'));
        $auth->remove($auth->getPermission('users.update'));
        $auth->remove($auth->getPermission('users.delete'));
        $auth->remove($auth->getPermission('usersManager'));
    }
}
