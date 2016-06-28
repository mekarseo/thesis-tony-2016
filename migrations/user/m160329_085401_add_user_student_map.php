<?php

use yii\db\Migration;
use yii\db\Schema;

class m160329_085401_add_user_student_map extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_student_map}}', [
            'user_id'           => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'csv_id'      => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'PRIMARY KEY (user_id)',
        ], $tableOptions);

        $this->addForeignKey('fk_map_user_id', '{{%user_student_map}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_map_csv_id', '{{%user_student_map}}', 'csv_id', '{{%user_personal_info}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_map_user_id', '{{%user_student_map}}');
        $this->dropForeignKey('fk_map_csv_id', '{{%user_student_map}}');

        $this->dropTable('{{%user_student_map}}');
    }
}
