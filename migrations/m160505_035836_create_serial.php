<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `serial`.
 */
class m160505_035836_create_serial extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%serial_code}}', [
            'id'      => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'serial'  => Schema::TYPE_STRING . '(13) NOT NULL',
            'create_time' => Schema::TYPE_STRING. '(20) NOT NULL',
            'expire_time' => Schema::TYPE_STRING. '(20) NOT NULL',
            'status'  => Schema::TYPE_STRING . '(10) NOT NULL',
            'PRIMARY KEY (id)',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%serial_code}}');
    }
}
