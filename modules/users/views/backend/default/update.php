<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use modules\users\Module;

/**
 * @var $this yii\web\View
 * @var $model modules\users\models\User
 */

$this->title = Module::translate('module', 'Update');
$this->params['title']['small'] = $model->userFullName;

$this->params['breadcrumbs'][] = ['label' => Module::translate('module', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->userFullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::translate('module', 'Update');
?>

<div class="users-backend-default-update">
    
    <?=$this->render('@modules/users/views/backend/default/tabs/_menu_tabs',['modelUsuario'=>$model]);?>

    <div class="box">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">

                    <?= $form->field($model, 'email')->textInput([
                        'maxlength' => true,
                        'placeholder' => true,
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => true,
                    ]) ?>

                    <?= $form->field($model, 'status')->dropDownList($model->statusesArray) ?>

                    <hr>

                    <?= $form->field($model->profile, 'email_gravatar')->textInput([
                        'maxlength' => true,
                        'placeholder' => true,
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                        echo $this->renderFile(Yii::getAlias('@modules/users/views/backend/default/_form.php'), [
                            'model' => $model,
                            'profile' => $model->profile,
                            'uploadFormModel' => $uploadFormModel,
                            'form'=>$form,
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <?= Html::submitButton('<span class="fas fa-save"></span> ' . Module::translate('module', 'Save'), [
                    'class' => 'btn btn-primary',
                    'name' => 'submit-button',
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
