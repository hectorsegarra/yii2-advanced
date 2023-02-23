<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datecontrol\DateControl; //Descomentar esto si hay fechas 

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionParticipantes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversacion-participantes-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class='col-lg-6'>
                    <?=$form->field($model, 'conversacion_id')->textInput()?>
                </div>
                <div class='col-lg-6'>
                    <?=$form->field($model, 'usuario_id')->textInput()?>
                </div>
            </div>
            <div class='row'>
                <div class='col-lg-6'>
                    <?=$form->field($model, 'entra_el')->widget(DateControl::classname(), [
                        'type'=>DateControl::FORMAT_DATETIME,
                        'ajaxConversion' => true,
                        'autoWidget' => true,
                    ])?>
                </div>
                <div class='col-lg-6'>
                    <?=$form->field($model, 'administrador')->checkbox()?>
                </div>
            </div>
            <div class='row'>
                <div class='col-lg-6'>
                    <?=$form->field($model, 'ultima_leida')->widget(DateControl::classname(), [
                        'type'=>DateControl::FORMAT_DATETIME,
                        'ajaxConversion' => true,
                        'autoWidget' => true,
                    ])?>
                </div>
                <div class='col-lg-6'>
                        </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <?= Html::submitButton('<span class="fas fa-save"></span> '.'Guardar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
