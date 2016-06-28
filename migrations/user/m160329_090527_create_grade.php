<?php

use yii\db\Migration;
use yii\db\Schema;

class m160329_090527_create_grade extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%grade_student}}', [
            'id'            => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'student_id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'grade'         => Schema::TYPE_DECIMAL . '(10,2) NOT NULL',
            'term'          => Schema::TYPE_STRING . '(6) NOT NULL',
            'create_at'     => Schema::TYPE_DATETIME . ' NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);
        
        $this->addForeignKey('fk_grade_user_id', '{{%grade_student}}', 'student_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_grade_user_id', '{{%grade_student}}');
        $this->dropTable('{{%grade_student}}');
    }
}
