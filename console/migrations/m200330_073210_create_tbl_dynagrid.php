<?php
use yii\db\Migration;

class m200330_073210_create_tbl_dynagrid extends Migration
{
    public function up()
    {
        $this->createTable('tbl_dynagrid', [
            'id' => $this->string(100)->notNull()->comment('Unique dynagrid setting identifier'),
            'filter_id' => $this->string(100)->null()->comment('Filter setting identifier'),
            'sort_id' => $this->string(100)->null()->comment('Sort setting identifier'),
            'data' => $this->string(5000)->null()->comment('Json encoded data for the dynagrid configuration')
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_General_ci COMMENT='Dynagrid personalization configuration settings'");
        $this->addPrimaryKey('id', 'tbl_dynagrid', 'id');
        $this->createIndex('tbl_dynagrid_FK1', 'tbl_dynagrid', 'filter_id');
        $this->createIndex('tbl_dynagrid_FK2', 'tbl_dynagrid', 'sort_id');
        $this->addForeignKey('tbl_dynagrid_FK1', 'tbl_dynagrid', 'filter_id', 'tbl_dynagrid_dtl', 'id', 'CASCADE');
        $this->addForeignKey('tbl_dynagrid_FK2', 'tbl_dynagrid', 'sort_id', 'tbl_dynagrid_dtl', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('tbl_dynagrid');
    }
}