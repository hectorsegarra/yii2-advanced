<style>
    .asunto{
        width: 20ch;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .mensaje-corto{
        width: 33ch;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<?php
use yii\web\View;
use yii\helpers\Html;
use modules\users\widgets\AvatarWidget;
use modules\conversacion\models\Conversacion;
use modules\conversacion\Module;


$conversaciones=Conversacion::getMisConversacionesConMensajesSinLeer();
$numeroConversacionesSinLeer=count($conversaciones);

?>

<li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="far fa-envelope"></i>
        <span class="label label-success"><?=$numeroConversacionesSinLeer?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><?= Module::translate('module', 'You have {num} unread conversations', ['num' => $numeroConversacionesSinLeer]) ?></li>
        <li>
            <ul class="menu">
                <?php
                if($conversaciones){
                    foreach ($conversaciones as $conversacion) {
                        $ultimoMensaje=$conversacion->ultimoMensaje;
                        ?>
                            <li>
                                <a href="/admin/conversacion/chat?conversacion_id=<?=$conversacion->id?>">
                                    <div class="pull-left">
                                        <?= AvatarWidget::widget([
                                            'user_id' => isset($ultimoMensaje) ? $ultimoMensaje->sender_id : Yii::$app->user->id,
                                        ]); ?>
                                    </div>
                                    <h4>
                                        <div class="asunto">
                                            <?=$conversacion->asunto?>
                                        </div>
                                        <small><i class="fas fa-clock"></i> <?=isset($ultimoMensaje) ? $ultimoMensaje->tiempoTranscurrido : '<span class="text-danger"></span>';?></small>
                                    </h4>
                                    <p class="mensaje-corto"><?=isset($ultimoMensaje) ? $ultimoMensaje->mensaje : '<span class="text-danger">Sin mensajes</span>';?></p>
                                </a>
                            </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </li>
        <li class="footer" ><a href="/admin/conversacion/ver-todas?tipo=personales"><span class="text-primary"><?=Module::translate('module', 'See All Messages')?></span></a></li>
    </ul>
</li>
