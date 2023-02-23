<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\search\ConversacionParticipantesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversacion-participantes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'conversacion_id') ?>

    <?= $form->field($model, 'usuario_id') ?>

    <?= $form->field($model, 'entra_el') ?>

    <?= $form->field($model, 'administrador') ?>

    <?php // echo $form->field($model, 'ultima_leida') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
