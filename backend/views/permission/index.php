<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\rbac\search\AuthItem */

$this->title = Yii::t('app', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
$this->blocks['content-header'] = '<small><span class="glyphicon glyphicon-list-alt text-primary"></span></small> '.$this->title;
?>
<div class="role-index  box box-primary">

    <?php
    Pjax::begin([
        'enablePushState' => false,
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
               'template'=>'<div class="btn-group" role="group">{view}</div>'
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<!-- Default box -->
<div class="box">
    <div class="box-header with-border box-primary">

    </div>
    <div class="box-body">

    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->