<?php

use yii\db\Migration;
use yii\db\Schema;

class m160319_083020_create_talent extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%talent_type}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->insert('{{%talent_type}}', ['id' => 1, 'name' => 'ด้านกีฬา']);
        $this->insert('{{%talent_type}}', ['id' => 2, 'name' => 'ด้านศิลปวัฒนธรรม']);

        $this->createTable('{{%talent_type_sub}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'type'  => Schema::TYPE_STRING . '(64) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(150) NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->addForeignKey('fk_sub_type', '{{%talent_type_sub}}', 'type', '{{%talent_type}}', 'name', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_sub_type', '{{%talent_type_sub}}');

        $this->dropTable('{{%talent_type}}');
        $this->dropTable('{{%talent_type_sub}}');
    }
}
