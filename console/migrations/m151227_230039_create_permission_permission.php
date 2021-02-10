<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_230039_create_permission_permission extends Migration
{
    public function up()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;

        // add "index" permission
        $permissionIndex = $auth->createPermission('permission.index');
        $permissionIndex->description = 'Список прав';
        $auth->add($permissionIndex);

        // add "view" permission
        $permissionCreate = $auth->createPermission('permission.view');
        $permissionCreate->description = 'Управление назначением прав';
        $auth->add($permissionCreate);


        // add role from permission
        $permissionManager = $auth->createPermission('permissionManager');
        $permissionManager->description = 'Управление разрешениями пользователей';
        $auth->add($permissionManager);
        $auth->addChild($permissionManager, $permissionIndex);
        $auth->addChild($permissionManager, $permissionCreate);

        //add role to admin
        $admin = $auth->getRole(\common\models\User::ROLE_IS_ADMIN);
        $auth->addChild($admin, $permissionManager);
    }

    public function down()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('permission.index'));
        $auth->remove($auth->getPermission('permission.view'));
        $auth->remove($auth->getPermission('permissionManager'));
    }
}
