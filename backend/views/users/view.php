<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\users\User */

$this->title = $model->getNameOrLogin();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary manage-users-view">

    <?= common\widgets\CrudMenu::widget(['model'=>$model,
        'visibleButtons' => [
            'create'=>Yii::$app->user->can('users.create'),
            'update'=>Yii::$app->user->can('users.update'),
            'list'=>Yii::$app->user->can('users.index'),
            'delete'=>Yii::$app->user->can('users.delete') && Yii::$app->user->getId() !== $model->id]]) ?>
<div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            ['label'=>$model->getAttributeLabel('status'),'value'=>$model->getStatusLabel()],
            'created_at:Date',
        ],
    ]) ?>
</div>
</div>
