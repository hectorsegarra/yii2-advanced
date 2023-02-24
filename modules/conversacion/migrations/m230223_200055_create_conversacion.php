<?php

namespace modules\conversacion\migrations;

use yii\db\Migration;



class m230223_200055_create_conversacion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{conversacion}}', [
            'id' => $this->primaryKey(),
            'asunto' => $this->string(250)->notNull(),
            'fecha_creacion' => $this->dateTime()->notNull(),
            'grupal' => $this->tinyInteger(4)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{conversacion}}');
    }
}

