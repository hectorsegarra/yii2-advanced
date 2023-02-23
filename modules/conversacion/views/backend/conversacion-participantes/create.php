<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionParticipantes */

$this->title = 'Nuevo Conversacion Participantes';
$this->params['breadcrumbs'][] = ['label' => 'Conversacion Participantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversacion-participantes-create">
    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
