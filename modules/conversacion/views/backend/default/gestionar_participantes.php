<style>
    li{
        list-style: none;
        margin-bottom: 4px;
    }
    table {
        border-collapse: collapse;
    }

    td, th {
        padding: 4px;
        border: 1px solid #ddd;
    }
    .asunto-grande{
        font-size:1.3em;
        color: #3C8DBC;
        margin-bottom:6px;
    }
</style>

<?php
use yii\web\View;
use yii\helpers\Html;
use modules\conversacion\Module;
use yii\widgets\ActiveForm; 
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;


$this->title = 'Conversaciones - Gestionar participantes';
$this->params['breadcrumbs'][] = ['label' => 'Conversaciones', 'url' => ['/conversacion/ver-todas?tipo=grupales']];
$this->params['breadcrumbs'][] = $this->title;

if(!$conversacion):
    echo '<div class="alert alert-danger">No se ha encontrado la conversación</div>';
    return;
endif;
?> 

<div class="asunto-grande">Conversación: <?=$conversacion->asunto?></div>

<div class="box box-primary">



    <?php $form = ActiveForm::begin([
        'action' => ['/conversacion/anadir-participante'],
    ]); ?>
        <div class="row">
            <div class="col-lg-12">
                <label class="control-label">Añadir un usuario</label>
            </div>
            <div class="col-lg-12" style="padding-left:28px;">
                <?= Html::hiddenInput('conversacion_id', $conversacion->id) ?>
                <?php 
                    $url = \yii\helpers\Url::to(['user-list?conversacion_id='.$conversacion->id]);
                    echo Select2::widget([
                        'name' => 'usuario_id',
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
            
            <div class="col-lg-12">
                <strong>Permisos otorgados a este usuario en esta conversación</strong>
            </div>
            <div class='col-lg-12' style="padding-left:28px;">
                <?= \yii\helpers\Html::checkbox('ver_mensajes_anteriores', false, ['label' => '<i class="fas fa-history" style="color: green"></i><span style="font-weight: normal;"> Ver mensajes anteriores a su entrada</span>']) ?>
            </div>
            <div class='col-lg-12' style="padding-left:28px;">
                <?= \yii\helpers\Html::checkbox('administrador', false, ['label' => '<i class="fas fa-star" style="color: #ffa500"></i><span style="font-weight: normal;"> Administrador</span>']) ?>    
            </div>
            <div class="col-lg-12">
                <button class="btn btn-success" type="submit"><i class="fas fa-user-plus"></i> Añadir participante</button>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<div class="box box-primary">
 
    <div><strong>Participantes actuales:</strong></div>

    <?php if($conversacion->conversacionParticipantes):?>


        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                    <th>Entrada</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($conversacion->conversacionParticipantes as $participante): ?>
                    <tr>
                        <td>
                            <?php if($participante->administrador==1): ?>
                                <i title="Usuario administrador" class="fas fa-star" style="color: #ffa500"></i>
                            <?php else: ?>
                                <i title="Usuario no administrador" class="fas fa-star" style="color: grey"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= $participante->usuario->userFullName ?></td>
                        <td>
                            <button title="Eliminar al participante de la conversación" class="eliminar-participante btn btn-danger btn-sm" data-id="<?= $participante->usuario->id ?>">Eliminar</button>
                            <?php if($participante->administrador==1): ?>
                                <button title="Quitar administración" class="quitar-administracion btn btn-warning btn-sm" data-id="<?= $participante->usuario->id ?>"><i class="fas fa-minus"></i> <i class="fas fa-star"></i></button>
                            <?php else: ?>
                                <button title="Dar administración" class="dar-administracion btn btn-success btn-sm" data-id="<?= $participante->usuario->id ?>"><i class="fas fa-plus"></i> <i class="fas fa-star"></i></button>
                            <?php endif; ?>
                        </td>
                        <td><?= $participante->entra_el ?></td>
                        <td>
                            <?php if($participante->ver_mensajes_anteriores==1): ?>
                                <i  title="Puede ver er mensajes anteriores a su entrada" class="fas fa-history" style="color: green"></i>
                            <?php else: ?>
                                <i title="No puede ver mensajes anteriores a su entrada" class="fas fa-ban" style="color: grey"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                        
                    
            </tbody>
        </table>
                 
        <br>
        <i class="fas fa-info-circle text-danger"></i> Si se le otortga a alguna perosna el poder de administrar la conversación le resultara posible dar de baja a alguien y volverlo a meter pudinedo mostrar los mensajes antiguos, ten cuidado con esto si no quieres que nadie vea los mensajes antiguos.
        <br>
        <i class="fas fa-info-circle text-danger"></i> Una vez metemos a alguien en la conversación, no hay forma de cambiar la opcion ver mensajes antiguos, tendriamos que darle de baja de la conversacion y despues darle de alta con la opcion modificada.
        <br>
        <i class="fas fa-info-circle text-danger"></i> La opción de ser administrador si que puede modificarse desde el boton adecuado, pero si se nombra a alguien administrador, esta persona podra quitarnos incluso a nosotros el poder de administrar el grupo.

    <?php else: ?>
        <div>No hay participantes en esta conversación</div>
    <?php endif; ?>


    <br>
            
</div>

<?php 
$script = <<< JS
    $('body').on('click', '.eliminar-participante', function() {
        var participanteId = $(this).data('id');
        var conversacionId = $conversacion->id;
        $.ajax({
            url: '/admin/conversacion/eliminar-participante',
            type: 'POST',
            data: { participanteId: participanteId, conversacionId: conversacionId },
            success: function(data) {
                //redirigir a la página de gestión de participantes
                location.reload();
            },
            error: function(xhr, status, error) {
                console.log('Error en la petición AJAX');
            }
        });
    });

    $('body').on('click', '.quitar-administracion', function() {
        var participanteId = $(this).data('id');
        var conversacionId = $conversacion->id;
        $.ajax({
            url: '/admin/conversacion/quitar-administracion',
            type: 'POST',
            data: { participanteId: participanteId, conversacionId: conversacionId },
            success: function(data) {
                if (data.success) {
                    //reload la pagina
                    location.reload();
                } else {
                    //reload la pagina
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error en la petición AJAX');
            }
        });
    });

    $('body').on('click', '.dar-administracion', function() {
        var participanteId = $(this).data('id');
        var conversacionId = $conversacion->id;
        $.ajax({
            url: '/admin/conversacion/dar-administracion',
            type: 'POST',
            data: { participanteId: participanteId, conversacionId: conversacionId },
            success: function(data) {
                if (data.success) {
                    //reload la pagina
                    location.reload();
                } else {
                    //reload la pagina
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error en la petición AJAX');
            }
        });
    });
JS;
$this->registerJs($script);
?>

