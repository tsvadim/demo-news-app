<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\rbac\AuthItem $model
 */

$this->title = Yii::t('app', 'Create Role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary auth-item-create">
    <?= common\widgets\CrudMenu::widget(['model' => $model,'primaryKey' => 'name']) ?>
    <div class="box-body">
	<?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
    </div>
</div>
