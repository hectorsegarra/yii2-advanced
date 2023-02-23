<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\Conversacion */

$this->title = 'Nueva conversación';

$this->params['breadcrumbs'][] = ['label' => 'Conversaciones', 'url' => ['/conversacion/ver-todas?tipo='.$tipo]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversacion-create">
    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
            'tipo' => $tipo,
        ]) ?>
    </div>
</div>
