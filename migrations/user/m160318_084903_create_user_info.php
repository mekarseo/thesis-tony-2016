<?php

use yii\db\Migration;
use yii\db\Schema;

class m160318_084903_create_user_info extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_personal_info}}', [
            'id'                => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'std_id'            => Schema::TYPE_STRING . '(13)',
            'personal_id'       => Schema::TYPE_STRING . '(13) NOT NULL',
            'first_name'        => Schema::TYPE_STRING . '(100) NOT NULL',
            'last_name'         => Schema::TYPE_STRING . '(100) NOT NULL',
            'birth_date'        => Schema::TYPE_DATE . ' NOT NULL',
            'mobile'            => Schema::TYPE_STRING . '(20) NOT NULL',
            'email'             => Schema::TYPE_STRING . '(150) NOT NULL',
            'address'           => Schema::TYPE_TEXT,
            'father'            => Schema::TYPE_STRING . '(200)',
            'father_mobile'     => Schema::TYPE_STRING . '(20)',
            'mother'            => Schema::TYPE_STRING . '(200)',
            'mother_mobile'     => Schema::TYPE_STRING . '(20)',
            'parent'            => Schema::TYPE_STRING . '(200)',
            'parent_mobile'     => Schema::TYPE_STRING . '(20)',
            'parent_address'    => Schema::TYPE_TEXT,
            'term'              => Schema::TYPE_STRING . '(6)',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createTable('{{%user_education_info}}', [
            'id'                => Schema::TYPE_INTEGER . '(13) NOT NULL',
            'old_school'        => Schema::TYPE_TEXT,
            'school_provice'    => Schema::TYPE_STRING . '(100)',
            'branch'            => Schema::TYPE_TEXT,
            'graduate'          => Schema::TYPE_STRING . '(200)',
            'gpa_graduation'    => Schema::TYPE_DECIMAL. '(10,2)',
            'level'             => Schema::TYPE_INTEGER . '(11)',
            'major'             => Schema::TYPE_STRING . '(250)',
            'faculty'           => Schema::TYPE_STRING . '(250)',
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createTable('{{%user_talent_info}}', [
            'id'                => Schema::TYPE_INTEGER . '(13) NOT NULL',
            'talent_type'       => Schema::TYPE_STRING . '(100)',
            'talent_sub'        => Schema::TYPE_STRING . '(100)',
            'talent_detail'     => Schema::TYPE_TEXT,
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->addForeignKey('fk_edu_personal', '{{%user_education_info}}', 'id', '{{%user_personal_info}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_edu_level', '{{%user_education_info}}', 'level', '{{%edu_level}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_talent_personal', '{{%user_talent_info}}', 'id', '{{%user_personal_info}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_edu_personal', '{{%user_education_info}}');
        $this->dropForeignKey('fk_edu_level', '{{%user_education_info}}');
        $this->dropForeignKey('fk_talent_personal', '{{%user_talent_info}}');

        $this->dropTable('{{%user_personal_info}}');
        $this->dropTable('{{%user_education_info}}');
        $this->dropTable('{{%user_talent_info}}');
    }
}