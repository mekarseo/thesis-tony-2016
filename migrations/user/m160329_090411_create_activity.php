<?php

use yii\db\Migration;
use yii\db\Schema;

class m160329_090411_create_activity extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%activity_status}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(100) NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createTable('{{%activity_student}}', [
            'id'            => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'student_id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'talent'        => Schema::TYPE_STRING . '(150) NOT NULL',
            'activity'      => Schema::TYPE_TEXT . ' NOT NULL',
            'section'       => Schema::TYPE_STRING . '(9) NOT NULL',
            'create_at'     => Schema::TYPE_DATETIME . ' NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createTable('{{%activity_history}}', [
            'id'            => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'activity_id'   => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'approve_id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'status'        => Schema::TYPE_STRING . '(100) NOT NULL',
            'comment'       => Schema::TYPE_TEXT . ' NOT NULL',
            'process_at'    => Schema::TYPE_DATETIME . ' NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->insert('{{%activity_status}}', ['id' => 1, 'name' => 'Pending']);
        $this->insert('{{%activity_status}}', ['id' => 2, 'name' => 'Cancel']);
        $this->insert('{{%activity_status}}', ['id' => 3, 'name' => 'Approve']);
        $this->insert('{{%activity_status}}', ['id' => 4, 'name' => 'Complete']);

        $this->createIndex('idx_activity_status_name', '{{%activity_status}}', 'name');
        $this->addForeignKey('fk_activity_student_id', '{{%activity_student}}', 'student_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_activity_talent', '{{%activity_student}}', 'talent', '{{%talent_type_sub}}', 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_activity_activity_id', '{{%activity_history}}', 'activity_id', '{{%activity_student}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_activity_approve_id', '{{%activity_history}}', 'approve_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_activity_status_name', '{{%activity_history}}', 'status', '{{%activity_status}}', 'name', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_activity_student_id', '{{%activity_student}}');
        $this->dropForeignKey('fk_activity_talent', '{{%activity_student}}');
        $this->dropForeignKey('fk_activity_activity_id', '{{%activity_history}}');
        $this->dropForeignKey('fk_activity_approve_id', '{{%activity_history}}');
        $this->dropForeignKey('fk_activity_status_name', '{{%activity_history}}');

        $this->dropTable('{{%activity_status}}');
        $this->dropTable('{{%activity_student}}');
        $this->dropTable('{{%activity_history}}');
    }
}
