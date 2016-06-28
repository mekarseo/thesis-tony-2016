<?php

use yii\db\Migration;
use yii\db\Schema;

class m160315_072017_create_edu_info extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        $this->createTable('{{%edu_type}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->insert('{{%edu_type}}', ['id' => 1, 'name' => 'Campus']);
        $this->insert('{{%edu_type}}', ['id' => 2, 'name' => 'Faculty']);
        $this->insert('{{%edu_type}}', ['id' => 3, 'name' => 'Division']);
        $this->insert('{{%edu_type}}', ['id' => 4, 'name' => 'Major']);

        $this->createTable('{{%edu_info}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'name'  => Schema::TYPE_TEXT,
            'type'  => Schema::TYPE_STRING .'(64) NOT NULL',
            'parent'    => Schema::TYPE_INTEGER .'(11) NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createIndex('idx_info_type', '{{%edu_info}}', 'type');
        $this->addForeignKey('fk_info_type', '{{%edu_info}}', 'type', '{{%edu_type}}', 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_info_parent', '{{%edu_info}}', 'parent', '{{%edu_info}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_info_type', '{{%edu_info}}');

        $this->dropTable('{{%edu_type}}');
        $this->dropTable('{{%edu_info}}');
    }
}
