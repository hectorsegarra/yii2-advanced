<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionMensaje */

$this->title = 'Update Conversacion Mensaje: ' . $model->conversacion_id;
$this->params['breadcrumbs'][] = ['label' => 'Conversacion Mensajes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->conversacion_id, 'url' => ['view', 'id' => $model->conversacion_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conversacion-mensaje-update">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
