<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\search\ConversacionMensajeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversacion-mensaje-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'conversacion_id') ?>

    <?= $form->field($model, 'sender_id') ?>

    <?= $form->field($model, 'mensaje') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'grupal') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
