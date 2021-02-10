<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\news\News */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app', 'News'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$this->blocks['content-header'] = '<small><span class="glyphicon glyphicon-edit text-primary-light"></span></small> '.$this->title;
?>
<div class="news-update">
    <div class="manage-users-form box box-primary">
        <?=\common\widgets\CrudMenu::widget(['model'=>$model,'visibleButtons'=>[
            'list'=>Yii::$app->user->can('news.index'),
            'create'=>Yii::$app->user->can('news.create'),
            'delete'=>Yii::$app->user->can('news.delete'),
            'view'=>(Yii::$app->user->can('news.index') || Yii::$app->user->can('news.create') || Yii::$app->user->can('news.update')),
        ]]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
