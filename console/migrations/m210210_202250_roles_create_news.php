<?php

use yii\db\Migration;

class m210210_202250_roles_create_news extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        // Create "Index" permission
        $permissionIndex = $auth->createPermission('news.index');
        $permissionIndex->description = Yii::t('app', 'List News');
        $auth->add($permissionIndex);

        // Create "Create" permission
        $permissionCreate = $auth->createPermission('news.create');
        $permissionCreate->description = Yii::t('app', 'Create News');
        $auth->add($permissionCreate);

        // Create "Update" permission
        $permissionUpdate = $auth->createPermission('news.update');
        $permissionUpdate->description = Yii::t('app', 'Edit News');
        $auth->add($permissionUpdate);

        // Create "Delete" permission
        $permissionDelete = $auth->createPermission('news.delete');
        $permissionDelete->description = Yii::t('app', 'Delete News');
        $auth->add($permissionDelete);

        // Create role 'newsManager'
        $roleManager = $auth->createPermission('newsManager');
        $roleManager->description = Yii::t('app', 'Manage news');
        $auth->add($roleManager);

        //Add permissions to role 'newsManager'    
        $auth->addChild($roleManager, $permissionIndex);
    
        $auth->addChild($roleManager, $permissionCreate);
    
        $auth->addChild($roleManager, $permissionUpdate);
    
        $auth->addChild($roleManager, $permissionDelete);

        //add role to admin
        $admin = $auth->getRole(\common\models\User::ROLE_IS_ADMIN);
        $auth->addChild($admin, $roleManager);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('news.index'));
        $auth->remove($auth->getPermission('news.create'));
        $auth->remove($auth->getPermission('news.update'));
        $auth->remove($auth->getPermission('news.delete'));
        $auth->remove($auth->getPermission('newsManager'));
    }

}