<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionParticipantes */

$this->title = 'Update Conversacion Participantes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conversacion Participantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conversacion-participantes-update">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
