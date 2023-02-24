<?php

namespace modules\conversacion\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `conversacion_mensaje`.
 * Has foreign keys to the tables:
 *
 * - `conversacion`
 * - `tbl_user`
 */
class m230224_074430_create_conversacion_mensaje extends Migration
{
    /**
     * {@inheritdoc}
     */ 
    public function safeUp()
    {
        $this->createTable('conversacion_mensaje', [
            'id' => $this->primaryKey()->comment('Id'),
            'conversacion_id' => $this->integer()->notNull()->comment('Conversación'),
            'sender_id' => $this->integer()->notNull()->comment('Emisor'),
            'mensaje' => $this->text()->notNull()->comment('Mensaje'),
            'created_at' => $this->datetime()->notNull()->comment('Creado el'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci');

        // creates index for column `sender_id`
        $this->createIndex(
            'idx-conversacion_mensaje-sender_id',
            'conversacion_mensaje',
            'sender_id'
        );

        // creates index for column `conversacion_id`
        $this->createIndex(
            'idx-conversacion_mensaje-conversacion_id',
            'conversacion_mensaje',
            'conversacion_id'
        );

        // add foreign key for table `conversacion`
        $this->addForeignKey(
            'fk-conversacion_mensaje-conversacion_id',
            'conversacion_mensaje',
            'conversacion_id',
            'conversacion',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // add foreign key for table `tbl_user`
        $this->addForeignKey(
            'fk-conversacion_mensaje-sender_id',
            'conversacion_mensaje',
            'sender_id',
            'tbl_user',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `conversacion`
        $this->dropForeignKey(
            'fk-conversacion_mensaje-conversacion_id',
            'conversacion_mensaje'
        );

        // drops foreign key for table `tbl_user`
        $this->dropForeignKey(
            'fk-conversacion_mensaje-sender_id',
            'conversacion_mensaje'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            'idx-conversacion_mensaje-sender_id',
            'conversacion_mensaje'
        );

        // drops index for column `conversacion_id`
        $this->dropIndex(
            'idx-conversacion_mensaje-conversacion_id',
            'conversacion_mensaje'
        );

        $this->dropTable('conversacion_mensaje');
    }
}