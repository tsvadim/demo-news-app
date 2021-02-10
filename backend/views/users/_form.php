<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\users\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manage-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(\common\models\User::getStatuses()) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>
    <?php
    $auth = Yii::$app->authManager;
    $roles = $auth->getRoles();
    $model->roles = array_keys(\yii\helpers\ArrayHelper::map($auth->getRolesByUser($model->id),'name','description'));
    echo $form->field($model, 'roles')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map($roles,'name','description'),
        'options' => ['placeholder' => Yii::t('app','Select roles'), 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>
    <?= $form->field($model, 'new_pass')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_pass_repeat')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
