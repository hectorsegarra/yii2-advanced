<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionMensaje */

$this->title = 'Nuevo Conversacion Mensaje';
$this->params['breadcrumbs'][] = ['label' => 'Conversacion Mensajes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversacion-mensaje-create">
    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
