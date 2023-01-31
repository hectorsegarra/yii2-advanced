<?php

use yii\db\Migration;

/**
 * Class m170605_000000_tbl_dynagrid_dtl
 */
class m170605_000000_tbl_dynagrid_dtl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tbl_dynagrid_dtl', [
            'id' => $this->string(100)->notNull()->comment('Unique dynagrid detail setting identifier'),
            'category' => $this->string(10)->notNull()->comment('Dynagrid detail setting category "filter" or "sort"'),
            'name' => $this->string(150)->notNull()->comment('Name to identify the dynagrid detail setting'),
            'data' => $this->string(5000)->null()->comment('Json encoded data for the dynagrid detail configuration'),
            'dynagrid_id' => $this->string(100)->notNull()->comment('Related dynagrid identifier'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_General_ci COMMENT="Dynagrid detail configuration settings"');

        $this->addPrimaryKey('id', 'tbl_dynagrid_dtl', 'id');
        $this->createIndex('tbl_dynagrid_dtl_UK1', 'tbl_dynagrid_dtl', ['name', 'category', 'dynagrid_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tbl_dynagrid_dtl');
    }
}