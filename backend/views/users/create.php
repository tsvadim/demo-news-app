<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\users\User */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary manage-users-create">

    <?= common\widgets\CrudMenu::widget(['model'=>$model,
        'visibleButtons' => [
        'create'=>Yii::$app->user->can('users.create'),
        'update'=>Yii::$app->user->can('users.update'),
        'list'=>Yii::$app->user->can('users.index'),
        'delete'=>Yii::$app->user->can('users.delete') && Yii::$app->user->getId() !== $model->id]]) ?>


    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
