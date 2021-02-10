<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\news\News */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = '<small><span class="glyphicon glyphicon-eye-open text-muted"></span></small> '.$this->title;
?>
<div class="news-view box box-primary">
    <?=\common\widgets\CrudMenu::widget(['model'=>$model,'visibleButtons'=>[
        'list'=>Yii::$app->user->can('news.index'),
        'create'=>Yii::$app->user->can('news.create'),
        'update'=>Yii::$app->user->can('news.update'),
        'delete'=>Yii::$app->user->can('news.delete'),
    ]]) ?>

    <div class="box-header">

        <?php
        echo Html::beginTag('ul');
        foreach ($model->trees as $tree){
            echo Html::beginTag('li');
            echo Html::encode($tree->name);
            echo Html::endTag('li');
        }
        echo Html::endTag('ul');
        ?>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'slug',
                'short_text:ntext',
                'text:ntext',
                'statusLabel',
                'created_at:datetime',
                'updated_at:datetime',
                ['attribute'=>'categories',
                    'value'=>'']
            ],
        ]) ?>
    </div>
</div>
