<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use modules\rbac\models\Role;
use modules\rbac\Module;

/* @var $this yii\web\View */
/* @var $model Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rbac-assign-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'role')->listBox(Role::rolesArray(), [
                'size' => 8
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ?
            '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ' . Module::translate(
                'module',
                'Create'
            ) : '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ' . Module::translate(
                'module',
                'Save'
            ),
            [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
            ]
        ) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
