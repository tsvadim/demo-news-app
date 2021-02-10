<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\models\rbac\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary auth-item-view">
    <?= common\widgets\CrudMenu::widget(['model' => $model,'primaryKey' => 'name']) ?>
    <div class="box-body">
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
           // 'ruleName',
            //'data:ntext',
        ],
    ]);
    ?>
    <div class="col-lg-5">
        <?= Yii::t('app', 'available') ?>:
        <?php
        echo Html::textInput('search_av', '', ['class' => 'form-control role-search', 'data-target' => 'available']) . '<br>';
        echo Html::listBox('roles', '', $available, [
            'id' => 'available',
            'multiple' => true,
            'size' => 20,
            'style' => 'width:100%']);
        ?>
    </div>
    <div class="col-lg-1">
        &nbsp;<br><br>
        <?php
        echo Html::a('>>', '#', ['class' => 'btn btn-success', 'data-action' => 'assign']) . '<br>';
        echo Html::a('<<', '#', ['class' => 'btn btn-success', 'data-action' => 'delete']) . '<br>';
        ?>
    </div>
    <div class="col-lg-5">
        <?= Yii::t('app', 'Assigned') ?>:
        <?php
        echo Html::textInput('search_asgn', '', ['class' => 'form-control role-search','data-target' => 'assigned']) . '<br>';
        echo Html::listBox('roles', '', $assigned, [
            'id' => 'assigned',
            'multiple' => true,
            'size' => 20,
            'style' => 'width:100%']);
        ?>
    </div>
    </div>
</div>
<?php
$this->render('_script',['name'=>$model->name]);
