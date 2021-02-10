<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\rbac\AuthItem $model
 */
$this->title = Yii::t('app', 'Update Role').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="box box-primary auth-item-update">
    <?= common\widgets\CrudMenu::widget(['model' => $model,'primaryKey' => 'name']) ?>
    <div class="box-body">
    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>
</div>
</div>
