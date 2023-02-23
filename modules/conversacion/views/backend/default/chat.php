<style>
    .mensaje{
        border:1px solid grey;
        border-radius: 4px;
        width: 90%;
        margin-bottom: 4px;
        padding: 4px;
    }
    .mio{
        float: right;
        background-color: #ceffce;
    }
    .otros{
        float: left;
        background-color: #fffff4;
    }
    .img-avatar{
        margin: auto 10px auto auto;
        width: 40px;
        height: 40px;
    }
    .nombre-usuario{
        font-weight:bold;
        color: #3C8DBC;
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 12px;
    }
    .creado-el{
        color: #8a8a8a;
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 12px;
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
use modules\users\widgets\AvatarWidget;
use modules\conversacion\Module;
use yii\widgets\ActiveForm; 

$this->title = 'Conversaciones - conversación';
$this->params['breadcrumbs'][] = ['label' => 'Conversaciones', 'url' => ['/conversacion/ver-todas?tipo=grupales']];
$this->params['breadcrumbs'][] = $this->title;

$user_id=Yii::$app->user->id;
?>

<div class="asunto-grande">Asunto: <?=$conversacion->asunto;?></div>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="input-group">
                <input name="mensaje" type="text" class="form-control" placeholder="Mensaje..." autofocus>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i> Enviar</button>
                </span>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<br/>

<div class="row">
    <div class="col-lg-12"> 
        <?php
        foreach ($conversacion->mensajesDeUnaConversacion as $mensaje) {
            ?> 
                <div class="mensaje <?=($mensaje->sender_id==Yii::$app->user->id) ? 'mio' : 'otros';?>">
                    <div class="pull-left">
                        <?= AvatarWidget::widget([
                            'user_id' => $mensaje->sender_id,
                            'imageOptions' => [
                                'class' => 'img-avatar img-circle'
                            ],
                        ]); ?>
                    </div>
                    <div class="nombre-usuario"><?=$mensaje->sender->userFullName;?></div>
                    <div>
                        <?=$mensaje->mensaje;?>
                        <div class="pull-right creado-el">
                            <?=date('d/m/Y H:i', strtotime($mensaje->created_at))?>
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>
    </div>
</div>
    
