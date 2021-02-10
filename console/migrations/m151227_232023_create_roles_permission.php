<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_232023_create_roles_permission extends Migration
{
    public function up()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;

        // add "index" permission
        $roleIndex = $auth->createPermission('role.index');
        $roleIndex->description = 'Список ролей';
        $auth->add($roleIndex);

        // add "create" permission
        $roleCreate = $auth->createPermission('role.create');
        $roleCreate->description = 'Создать роль';
        $auth->add($roleCreate);

        // add "update" permission
        $roleUpdate = $auth->createPermission('role.update');
        $roleUpdate->description = 'Редактировать роль';
        $auth->add($roleUpdate);

        // add "delete" permission
        $roleDelete = $auth->createPermission('role.delete');
        $roleDelete->description = 'Удалить роль';
        $auth->add($roleDelete);


        // add role from permission
        $roleManager = $auth->createPermission('roleManager');
        $roleManager->description = 'Управление ролями пользователей';
        $auth->add($roleManager);
        $auth->addChild($roleManager, $roleIndex);
        $auth->addChild($roleManager, $roleCreate);
        $auth->addChild($roleManager, $roleUpdate);
        $auth->addChild($roleManager, $roleDelete);

        //add role to admin
        $admin = $auth->getRole(\common\models\User::ROLE_IS_ADMIN);
        $auth->addChild($admin, $roleManager);
    }

    public function down()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('role.index'));
        $auth->remove($auth->getPermission('role.create'));
        $auth->remove($auth->getPermission('role.update'));
        $auth->remove($auth->getPermission('role.delete'));
        $auth->remove($auth->getPermission('roleManager'));
    }
}
