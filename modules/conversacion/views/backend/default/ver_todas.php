<style>
    .mensaje-container{
        margin: 0;
        padding: 10px 10px;
        display: block;
        white-space: nowrap;
        
        text-decoration: none;
        font-size: 14px;
        text-align: left;
    }
    .acciones-container{
        padding: 10px 10px;
    }
    .mensaje-container:hover{
        background-color:#f4f4f4;
    }
    .mensaje-container img{
        margin: auto 10px auto auto;
        width: 40px;
        height: 40px;
    }
    .mensaje-container h4{
        padding: 0;
        margin: 0 0 0 45px;
        color: #444444;
        font-size: 15px;
        position: relative;
    }
    .mensjae-container small{
        color: #999999;
        font-size: 10px;
        position: absolute;
        top: 0;
        right: 0;
    }

    .mensaje-largo{
        clear: both;
        margin: 0 0 0 45px;
        font-size: 12px;
        color: #888888;
    }

    .texto-gris{
        color: #888888;
    }

    .borde-inferior-row{
        border-bottom: 1px solid #f4f4f4;
    }
</style>

<?php
use yii\web\View;
use yii\helpers\Html;
use modules\users\widgets\AvatarWidget;
use modules\conversacion\Module;
use modules\conversacion\models\ConversacionParticipantes;

$this->title = 'Conversaciones';
$this->params['breadcrumbs'][] = $this->title;
?> 

<?=$this->render('@modules/conversacion/views/backend/default/tabs/_menu_tabs');?>

<div class="box box-primary">

    
    <?= Html::a('<i class="far fa-edit"></i> Crear Conversacion', ['conversacion/create',"tipo"=>$tipo], ['class' => 'btn btn-success pull-right', 'style' => 'margin-top: 4px; margin-bottom: 4px;']) ?>
    
    <div style="clear:both; border-bottom:1px dashed #3C8DBC;">
    </div>

    <?php
        foreach ($conversaciones as $conversacion) { 
            $ultimoMensaje=$conversacion->ultimoMensaje; 
            ?>
                <div class="row borde-inferior-row">
                    <div class="col-lg-10">
                        <a class="mensaje-container" href="/admin/conversacion/chat?conversacion_id=<?=$conversacion->id?>">
                            <div class="pull-left">
                                <div>
                                    <?php
                                        //si es personal
                                        if($tipo=="personales"){
                                            //pongo el avatar del otro participante de la conversacion
                                            $participantes=$conversacion->conversacionParticipantes;
                                            foreach ($participantes as $participante) {
                                                if($participante->usuario_id!=Yii::$app->user->id){
                                                    echo AvatarWidget::widget([
                                                        'user_id' => $participante->usuario_id,
                                                        'imageOptions' => [
                                                            'class' => 'img-circle',
                                                            'title' => $participante->usuario->userFullName,
                                                        ],
                                                    ]);
                                                }
                                            }
                                        }else{
                                            //pongo el avatar de quien haya enviado el ultimo mensaje
                                            echo AvatarWidget::widget(['user_id' => isset($ultimoMensaje) ? $ultimoMensaje->sender_id : Yii::$app->user->id]);
                                        }
                                    ?>
                                </div>
                            </div>
                            <h4>
                                <div class="asunto-sin-cortar pull-left">
                                    <?php
                                        if($tipo=="personales"){
                                    ?>
                                            <span class="texto-gris"><i class="fas fa-user"></i> </span><?=$participante->usuario->userFullName?>
                                            <br>
                                    <?php
                                        }
                                    ?>
                                    <span class="texto-gris">Asunto: </span><?=$conversacion->asunto?>
                                </div>
                                <small class="pull-right"><i class="fas fa-clock"></i> <?=isset($ultimoMensaje) ? $ultimoMensaje->tiempoTranscurrido : '<span class="text-danger">...</span>';?></small>
                            </h4>
                            <div class="mensaje-largo">Último mensaje: <?=isset($ultimoMensaje) ? $ultimoMensaje->mensaje : '<span class="text-danger">Sin mensajes</span>';?></div>
                        </a>
                    </div>
                    <div class="col-lg-2 ">
                        <div class="acciones-container">
                            <a href="/admin/conversacion/salir-de-la-conversacion?conversacion_id=<?=$conversacion->id?>"><div class="btn btn-danger btn-block btn-sm">Salir</div></a>
                                                       
                            <?php 
                                    $usuario_id=Yii::$app->user->id;
                                    $UsuarioActualComoParticipante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion->id, ':usuario_id' => $usuario_id])->one();
                                    if($UsuarioActualComoParticipante!=null){
                                        if($tipo=="personales"){
                                            echo '<a href="/admin/conversacion/convertir-en-grupal?conversacion_id='.$conversacion->id.'"><div class="btn btn-primary btn-block btn-sm">Hacer grupal</div></a>';
                                        }
                                        if($tipo=="grupales"){
                                            echo '<a href="/admin/conversacion/gestionar-participantes?conversacion_id='.$conversacion->id.'"><div class="btn btn-primary btn-block btn-sm">Participantes</div></a>';
                                        }
                                    } 
                                ?>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>
</div>