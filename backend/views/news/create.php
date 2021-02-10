<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\news\News */

$this->title = Yii::t('app', 'Create News');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = '<small><span class="glyphicon glyphicon-list-alt text-primary"></span></small> '.$this->title;
?>
<div class="news-create">
    <div class="manage-users-form box box-primary">
       <?=\common\widgets\CrudMenu::widget() ?>
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>
    </div>

</div>