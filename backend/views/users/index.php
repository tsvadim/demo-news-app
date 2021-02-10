<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\users\search\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary manage-users-index">

    <?= common\widgets\CrudMenu::widget() ?>
<?php Pjax::begin([
    'options' => [
        'id' => "pjax-" . $searchModel->formName(),
        'class' => 'box-body table-responsive no-padding',
    ]
]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'id',
            'username',
            'email:email',
            [   'class' => \yii\grid\DataColumn::className(),
                'attribute' => 'status',
                'format' => 'text',
                'value'=>function ($model, $key, $index, $grid){
                    return $model->statusLabel;
                },
                'filterInputOptions' => ['class'=>'form-control', /*'class'=>'select-multiple'*/],
                'filter' => \common\models\User::getStatuses()
            ],
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'create_date',
                    'language' => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'=>['class'=>'form-control'],
                ]),
                'format' => ['datetime','php:d-m-Y H:i'],
                'filterInputOptions' => ['class'=>'form-control', /*'class'=>'select-multiple'*/],
            ],
            ['class' => 'common\grid\ActionColumnGroup',
                'template'=> '<div class="btn-group" role="group">{view} {update} {delete}</div>',
                'visibleButtons' => [
                        'index'=>Yii::$app->user->can('users.index'),
                        'create'=>Yii::$app->user->can('users.create'),
                        'update'=>Yii::$app->user->can('users.update'),
                        'delete'=>Yii::$app->user->can('users.delete'),
                        'view'=>Yii::$app->user->can('users.index')
                            || Yii::$app->user->can('users.create')
                            || Yii::$app->user->can('users.update'),
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
