<?php

namespace modules\conversacion\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `conversacion_participantes`.
 * Has foreign keys to the tables:
 *
 * - `conversacion`
 * - `tbl_user`
 */
class m230224_074912_create_conversacion_participantes extends Migration
{ 
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conversacion_participantes', [
            'id' => $this->integer(11)->notNull()->comment('Id'),
            'conversacion_id' => $this->integer(11)->notNull()->comment('Conversación'),
            'usuario_id' => $this->integer(11)->notNull()->comment('Usuario'),
            'entra_el' => $this->datetime()->notNull()->comment('Entra el día'),
            'administrador' => $this->integer(1)->notNull()->comment('Administrador'),
            'ultima_leida' => $this->datetime()->defaultValue(null)->comment('Última leída'),
            'ver_mensajes_anteriores' => $this->integer(1)->notNull()->comment('Permiso para ver mensajes anteriores a su inserción'),
        ]);

        $this->addPrimaryKey('PK_conversacion_participantes', 'conversacion_participantes', 'id');

        // creates index for column `conversacion_id`
        $this->createIndex(
            'idx-conversacion_participantes-conversacion_id',
            'conversacion_participantes',
            'conversacion_id'
        );

        // creates index for column `usuario_id`
        $this->createIndex(
            'idx-conversacion_participantes-usuario_id',
            'conversacion_participantes',
            'usuario_id'
        );

        // add foreign key for table `conversacion`
        $this->addForeignKey(
            'fk-conversacion_participantes-conversacion_id',
            'conversacion_participantes',
            'conversacion_id',
            'conversacion',
            'id',
            'CASCADE'
        );

        // add foreign key for table `tbl_user`
        $this->addForeignKey(
            'fk-conversacion_participantes-usuario_id',
            'conversacion_participantes',
            'usuario_id',
            'tbl_user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `conversacion`
        $this->dropForeignKey(
            'fk-conversacion_participantes-conversacion_id',
            'conversacion_participantes'
        );

        // drops foreign key for table `tbl_user`
        $this->dropForeignKey(
            'fk-conversacion_participantes-usuario_id',
            'conversacion_participantes'
        );

        // drops index for column `conversacion_id`
        $this->dropIndex(
            'idx-conversacion_participantes-conversacion_id',
            'conversacion_participantes'
        );

        // drops index for column `usuario_id`
        $this->dropIndex(
            'idx-conversacion_participantes-usuario_id',
            'conversacion_participantes'
        );

        $this->dropTable('conversacion_participantes');
    }
}