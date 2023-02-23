<?php

namespace modules\conversacion\models;

use Yii;
use modules\users\models\User;
use yii\db\Query;

/**
 * This is the model class for table "conversacion".
 *
 * @property int $id Id
 * @property string $asunto Asunto
 * @property string $tiempoTranscurrido TiempoTranscurrido 
 * @property string $fecha_creacion Fecha de creación
 * @property int $grupal Grupal
 *
 * @property ConversacionMensaje[] $conversacionMensajes
 * @property MensajesDeUnaConversacion[] $mensajesDeUnaConversacion
 * @property ConversacionParticipantes[] $conversacionParticipantes
 * @property ConversacionMensaje $ultimoMensaje
 * @property Conversacion $misConversacionesConMensajesSinLeer
 * @property Conversacion $misConversaciones
 */
class Conversacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asunto', 'fecha_creacion','grupal'], 'required'],
            [['grupal'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['asunto'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'asunto' => 'Asunto',
            'fecha_creacion' => 'Fecha de creación',
            'grupal' => 'Grupal',
        ];
    }
 
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversacionMensajes()
    {
        return $this->hasMany(ConversacionMensaje::className(), ['conversacion_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getMensajesDeUnaConversacion()
    {
        //ver si el usuario actual puede ver esta conversacion porque esta incluido en conversacionParticipantes
        $usuarioActual=Yii::$app->user->id;
        $conversacionParticipante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id AND usuario_id=:usuario_id',['usuario_id'=>$usuarioActual, ':conversacion_id' => $this->id])->one();
        
        if($conversacionParticipante){
            //ver si el usuario participante tiene elcampo activo "ver_mensajes_anteriores" en conversacionParticipantes y actuar en consecuencia
            if($conversacionParticipante->ver_mensajes_anteriores==1){
                //si tiene permiso para ver los mensjaes con una fecha anterior a la fecha de su entrada en la conversacion
                return $this->hasMany(ConversacionMensaje::className(), ['conversacion_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
            }else{
                //si no tiene permiso para ver los mensjaes con una fecha anterior a la fecha de su entrada en la conversacion
                return $this->hasMany(ConversacionMensaje::className(), ['conversacion_id' => 'id'])->where('created_at>=:created_at',[':created_at'=>$conversacionParticipante->entra_el])->orderBy(['created_at' => SORT_DESC]);
            }
        }else{
            //Aqui lo que hago es enviar una relacion con un where imposible porque si envio null me da error.
            return $this->hasMany(ConversacionMensaje::className(), ['conversacion_id' => 'id'])->where('1=0');
        }
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversacionParticipantes()
    {
        return $this->hasMany(ConversacionParticipantes::className(), ['conversacion_id' => 'id']);
    }

    public function getConversacionParticipante($usuario_id)
    {
        return $this->hasOne(ConversacionParticipantes::className(), ['conversacion_id' => 'id'])
                    ->where(['usuario_id' => $usuario_id]);
    }

    /*
    Tipos:
        null = todas
        personales = grupal=0
        grupales = grupal = 1
    */
    public static function getMisConversaciones($tipo=null){
        if($tipo=="personales"){
            $condicionEstra="grupal=0";
        }else if($tipo=="grupales"){
            $condicionEstra="grupal=1";
        }else{
            $condicionEstra="1=1";
        }
        return Conversacion::find()
            ->innerJoinWith(['conversacionParticipantes as cv'])
            ->where(['cv.usuario_id' => Yii::$app->user->id])
            ->andWhere($condicionEstra)
            ->orderBy(['fecha_creacion' => SORT_DESC])
            ->all();

    }
    
 
    public static function getMisConversacionesConMensajesSinLeer(){
        //obtengo las conversaciones en las que el usaario actual esta invloucrado
        $conversaciones=Conversacion::getMisConversaciones();
        //obtengo las conversaciones en las que el usaario actual esta invloucrado y tiene mensajes sin leer
        $conversacionesConMensajesSinLeer=[];
        foreach($conversaciones as $conversacion){
            $ultimoMensaje=$conversacion->getUltimoMensaje();
            if($ultimoMensaje){
                if($ultimoMensaje->created_at>$conversacion->getConversacionParticipante(Yii::$app->user->id)->one()->ultima_leida){
                    $conversacionesConMensajesSinLeer[]=$conversacion;
                }
            }
        }
        return $conversacionesConMensajesSinLeer;

    }

    
    

  

    public function getUltimoMensaje(){
        return ConversacionMensaje::find()
            ->where(['conversacion_id' => $this->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }

    public function getTiempoTranscurrido(){
        $current_time = time();
        $datetime = strtotime($this->fecha_creacion);
        $time_elapsed = $current_time - $datetime;
    
        if ($time_elapsed < 60) {
            return "$time_elapsed segundos";
        } elseif ($time_elapsed < 3600) {
            return floor($time_elapsed / 60) . " min.";
        } elseif ($time_elapsed < 86400) {
            return floor($time_elapsed / 3600) . " horas";
        } elseif ($time_elapsed < 31536000) {
            return floor($time_elapsed / 86400) . " días";
        } else {
            return floor($time_elapsed / 31536000) . " años";
        }
    }

}
