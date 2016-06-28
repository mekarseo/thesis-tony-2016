<?php

use yii\db\Migration;
use yii\db\Schema;

class m160317_080223_add_edu_level extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        $this->createTable('{{%edu_level}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(100) NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->insert('{{%edu_level}}', ['id' => 1, 'name' => 'ปวช.']);
        $this->insert('{{%edu_level}}', ['id' => 2, 'name' => 'ป.ตรี']);
        $this->insert('{{%edu_level}}', ['id' => 3, 'name' => 'ป.ตรี (ต่อเนื่อง)']);
        $this->insert('{{%edu_level}}', ['id' => 4, 'name' => 'ป.โท']);
        $this->insert('{{%edu_level}}', ['id' => 5, 'name' => 'ป.เอก']);

        $this->createTable('{{%edu_assign}}', [
            'major_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'levels' => Schema::TYPE_TEXT,
            'PRIMARY KEY (major_id)',
        ], $tableOptions);

        $this->addForeignKey('fk_assign_level', '{{%edu_assign}}', 'major_id', '{{%edu_info}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_assign_level', '{{%edu_assign}}');

        $this->dropTable('{{%edu_level}}');
        $this->dropTable('{{%edu_assign}}');
    }
}
