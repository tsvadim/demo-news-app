<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\rbac\AuthItem $searchModel
 */
$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index box box-primary">
        <?= common\widgets\CrudMenu::widget() ?>
    <?php
    Pjax::begin([
        'enablePushState'=>false,
        'options' => [
            'id' => "pjax-" . $searchModel->formName(),
            'class' => 'box-body table-responsive no-padding',
        ]
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => Yii::t('app', 'Name'),
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('app', 'Description'),
            ],
            ['class' => 'common\grid\ActionColumnGroup',
                'template'=> '<div class="btn-group" role="group">{view} {update} {delete}</div>',
              'visibleButtons' => [
                      'view'=>Yii::$app->user->can('role.create') || Yii::$app->user->can('role.update'),
                      'update'=>function($model){
                          return !in_array($model->name  ,[\common\models\User::ROLE_IS_ADMIN]) &&\Yii::$app->user->can('role.update');
                          },
                      'delete'=>function($model){
                          return !in_array($model->name  ,[\common\models\User::ROLE_IS_ADMIN]) && Yii::$app->user->can('role.delete');
                      },
              ]
              /*  'buttons'=>[
                    'update'=>function ($url, $model, $key) {
                        if(!\Yii::$app->user->can('role.update'))
                            return null;

                        if(in_array($model->name  ,[\common\models\User::ROLE_IS_ADMIN]))
                                return null;

                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                            ]);
                        },
                    'delete'=> function ($url, $model, $key) {
                        if(!\Yii::$app->user->can('role.delete'))
                            return null;

                            if(in_array($model->name  ,[\common\models\User::ROLE_IS_ADMIN]))
                                return null;

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    }
                ]*/
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>
