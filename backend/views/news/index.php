<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = '<small><span class="glyphicon glyphicon-list-alt text-primary"></span></small> '.$this->title;
?>
<div class="news-index box box-primary">
   <?=\common\widgets\CrudMenu::widget() ?>
    <?php Pjax::begin(); ?>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                'name',
                'short_text:ntext',
                 'statusLabel',
                // 'created_at',
                // 'updated_at',

                ['class' => 'common\grid\ActionColumnGroup',
                    'visibleButtons'=>[
                        'delete' => Yii::$app->user->can('news.delete'),
                        'update' => (Yii::$app->user->can('news.update')),
                        'view' =>(Yii::$app->user->can('news.create') || Yii::$app->user->can('news.update')),
                    ]
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
