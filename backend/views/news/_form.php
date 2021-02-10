<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\news\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
    echo \kartik\tree\TreeViewInput::widget([
            'model' => $model,
        'attribute' => 'inputTree',
//        'name' => 'inputTree',
//        'value' => $model->inputTree, // preselected values
        'query' => \common\models\Tree::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => 'Store'],
        'rootOptions' => ['label'=>'<i class="fas fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => false,
        'multiple' => true,
        'options' => ['disabled' => false]
    ]);
    ?>
    <?= $form->field($model, 'short_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(\backend\models\news\News::getStatuses()) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
