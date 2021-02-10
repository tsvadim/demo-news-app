<?php
use common\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title =  Yii::t('app','Welcome to admin panel');

?>
<?php $this->beginBlock('content-header') ?>
<?php $this->endBlock() ?>

<!-- Default box -->
<div class="box">
    <div class="box-header with-border box-primary">
        <div class="jumbotron">

            <?php if(Yii::$app->user->isGuest){ ?>
                <p><a class="btn btn-lg btn-success" href="<?=\yii\helpers\Url::to(['site/login'])?>">Войти</a></p>
            <?php }else{ ?>
                <h1>Добро пожаловать!</h1>
                <p class="lead">С возвращением <?= Yii::$app->user->identity ? Yii::$app->user->identity->getNameOrLogin() : null ?>, немного статистики для вас</p>
            <?php }  ?>
        </div>
    </div>

    <!-- /.box-body -->
    <div class="box-footer">

        <div class="col-md-10">
            <?= \kartik\tree\TreeView::widget([
                // single query fetch to render the tree
                // use the Product model you have in the previous step
                'query' => \common\models\Tree::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => Yii::t('app','Categories')],
                'rootOptions' => ['label'=>'<span class="text-primary">'.Yii::t('app','Categories').'</span>'],
                'topRootAsHeading' => true, // this will override the headingOptions
                'fontAwesome' => true,
                'isAdmin' => true,
                'iconEditSettings'=> [
                    'show' => 'none',
                ],
                'softDelete' => true,
                'cacheSettings' => ['enableCache' => true],
                'showIDAttribute'=>true,
                'showNameAttribute'=>true,
//                    'nodeAddlViews' => [
//                        \kartik\tree\Module::VIEW_PART_2 => '@backend/views/category/_form.php',
//                    ]
            ]);
            ?>
        </div>

    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->
