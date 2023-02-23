<?php

namespace modules\conversacion\models;

use Yii;
use modules\users\models\User;

/**
 * This is the model class for table "conversacion_mensaje".
 *
 * @property int $id Id
 * @property int $conversacion_id Conversación
 * @property int $sender_id Emisor
 * @property string $mensaje Mensaje
 * @property string $created_at Creado el
 * @property string $tiempoTranscurrido TiempoTranscurrido 
 *
 * @property Conversacion $conversacion
 * @property User $sender
 */
class ConversacionMensaje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversacion_mensaje';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conversacion_id', 'sender_id', 'mensaje', 'created_at'], 'required'],
            [['conversacion_id', 'sender_id'], 'integer'],
            [['mensaje'], 'string'],
            [['created_at'], 'safe'],
            [['conversacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversacion::className(), 'targetAttribute' => ['conversacion_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'conversacion_id' => 'Conversación',
            'sender_id' => 'Emisor',
            'mensaje' => 'Mensaje',
            'created_at' => 'Creado el',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversacion()
    {
        return $this->hasOne(Conversacion::className(), ['id' => 'conversacion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    public function getTiempoTranscurrido(){
        $current_time = time();
        $datetime = strtotime($this->created_at);
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
