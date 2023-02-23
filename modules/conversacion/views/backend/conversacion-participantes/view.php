<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model modules\conversacion\models\ConversacionParticipantes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conversacion Participantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="conversacion-participantes-view">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="pull-right">
                <p>
                    <?= Html::a(
                        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> ' .
                        'Update',
                        ['update', 'id' => $model->id],
                        ['class' => 'btn btn-primary']
                    ) ?>
                    <?= Html::a(
                        '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> ' .
                        'Delete',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]
                    ) ?>
                </p>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                                    'id',
                    'conversacion_id',
                    'usuario_id',
                    'entra_el',
                    'administrador',
                    'ultima_leida',
                ],
            ]) ?>
        </div>
    </div>
</div>
