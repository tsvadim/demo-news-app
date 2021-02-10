<?php

use yii\db\Migration;

class m141204_121825_create_rbac extends Migration
{
    public function up()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = \Yii::$app->authManager;
        $auth->removeAll();
        $admin = $auth->createRole(\common\models\User::ROLE_IS_ADMIN);
        $admin->description = 'Администратор';
        $auth->add($admin);
    }

    public function down()
    {
        /** @var yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
