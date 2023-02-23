<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datecontrol\DateControl; //Descomentar esto si hay fechas 
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\Conversacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversacion-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class='col-lg-6'>
                    <?=$form->field($model, 'asunto')->textInput(['maxlength' => true])?>
                </div>
                <?php 
                    if($tipo == 'personales'){
                        ?>
                            <div class='col-lg-6'>
                                <label for="otro_usuario_id" class="control-label">Selecciona el usuario para la conversación</label>
                                <?php 
                                    $url = \yii\helpers\Url::to(['/conversacion/default/user-list']);
                                    echo Select2::widget([
                                        'name' => 'otro_usuario_id',
                                        'options' => ['multiple'=>false, 'placeholder' => 'Busca un usuario ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 3,
                                            'language' => [
                                                'errorLoading' => new JsExpression("function () { return 'Esperando resultados...'; }"),
                                            ],
                                            'ajax' => [
                                                'url' => $url,
                                                'dataType' => 'json',
                                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                            ],
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function(user) { return user.text; }'),
                                            'templateSelection' => new JsExpression('function (user) { return user.text; }'),
                                        ],
                                    ]);
                                ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <div class='row'>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <?= Html::submitButton('<span class="fas fa-save"></span> '.'Crear conversación', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
