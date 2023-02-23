<?php

namespace modules\conversacion\models;

use Yii;
use modules\users\models\User;

/**
 * This is the model class for table "conversacion_participantes".
 *
 * @property int $id Id
 * @property int $conversacion_id Conversación
 * @property int $usuario_id Usuario
 * @property string $entra_el Entra el día
 * @property int $administrador Administrador
 * @property string $ultima_leida Última leída
 * @property int $ver_mensajes_anteriores Permiso para ver mensajes anteriores a su inserción
 *
 * @property Conversacion $conversacion
 * @property User $usuario
 */
class ConversacionParticipantes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversacion_participantes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conversacion_id', 'usuario_id', 'entra_el', 'administrador', 'ver_mensajes_anteriores'], 'required'],
            [['conversacion_id', 'usuario_id', 'administrador', 'ver_mensajes_anteriores'], 'integer'],
            [['entra_el', 'ultima_leida'], 'safe'],
            [['conversacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversacion::className(), 'targetAttribute' => ['conversacion_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_id' => 'id']],
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
            'usuario_id' => 'Usuario',
            'entra_el' => 'Entra el día',
            'administrador' => 'Administrador',
            'ultima_leida' => 'Última leída',
            'ver_mensajes_anteriores' => 'Permiso para ver mensajes anteriores a su inserción',
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
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }

}
