<?php

use yii\db\Migration;
use yii\db\Schema;

class m160316_185144_create_address_info extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%address_provice}}', [
            'id'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name'  => Schema::TYPE_STRING . '(150) NOT NULL',
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->insert('{{%address_provice}}', ['id' => 1, 'name' => 'กรุงเทพมหานคร']);
        $this->insert('{{%address_provice}}', ['id' => 2, 'name' => 'สมุทรปราการ']);
        $this->insert('{{%address_provice}}', ['id' => 3, 'name' => 'นนทบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 4, 'name' => 'ปทุมธานี']);
        $this->insert('{{%address_provice}}', ['id' => 5, 'name' => 'พระนครศรีอยุธยา']);
        $this->insert('{{%address_provice}}', ['id' => 6, 'name' => 'อ่างทอง']);
        $this->insert('{{%address_provice}}', ['id' => 7, 'name' => 'ลพบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 8, 'name' => 'สิงห์บุรี']);
        $this->insert('{{%address_provice}}', ['id' => 9, 'name' => 'ชัยนาท']);
        $this->insert('{{%address_provice}}', ['id' => 10, 'name' => 'สระบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 11, 'name' => 'ชลบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 12, 'name' => 'ระยอง']);
        $this->insert('{{%address_provice}}', ['id' => 13, 'name' => 'จันทบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 14, 'name' => 'ตราด']);
        $this->insert('{{%address_provice}}', ['id' => 15, 'name' => 'ฉะเชิงเทรา']);
        $this->insert('{{%address_provice}}', ['id' => 16, 'name' => 'ปราจีนบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 17, 'name' => 'นครนายก']);
        $this->insert('{{%address_provice}}', ['id' => 18, 'name' => 'สระแก้ว']);
        $this->insert('{{%address_provice}}', ['id' => 19, 'name' => 'นครราชสีมา']);
        $this->insert('{{%address_provice}}', ['id' => 20, 'name' => 'บุรีรัมย์']);
        $this->insert('{{%address_provice}}', ['id' => 21, 'name' => 'สุรินทร์']);
        $this->insert('{{%address_provice}}', ['id' => 22, 'name' => 'ศรีสะเกษ']);
        $this->insert('{{%address_provice}}', ['id' => 23, 'name' => 'อุบลราชธานี']);
        $this->insert('{{%address_provice}}', ['id' => 24, 'name' => 'ยโสธร']);
        $this->insert('{{%address_provice}}', ['id' => 25, 'name' => 'ชัยภูมิ']);
        $this->insert('{{%address_provice}}', ['id' => 26, 'name' => 'อำนาจเจริญ']);
        $this->insert('{{%address_provice}}', ['id' => 27, 'name' => 'หนองบัวลำภู']);
        $this->insert('{{%address_provice}}', ['id' => 28, 'name' => 'ขอนแก่น']);
        $this->insert('{{%address_provice}}', ['id' => 29, 'name' => 'อุดรธานี']);
        $this->insert('{{%address_provice}}', ['id' => 30, 'name' => 'เลย']);
        $this->insert('{{%address_provice}}', ['id' => 31, 'name' => 'หนองคาย']);
        $this->insert('{{%address_provice}}', ['id' => 32, 'name' => 'มหาสารคาม']);
        $this->insert('{{%address_provice}}', ['id' => 33, 'name' => 'ร้อยเอ็ด']);
        $this->insert('{{%address_provice}}', ['id' => 34, 'name' => 'กาฬสินธุ์']);
        $this->insert('{{%address_provice}}', ['id' => 35, 'name' => 'สกลนคร']);
        $this->insert('{{%address_provice}}', ['id' => 36, 'name' => 'นครพนม']);
        $this->insert('{{%address_provice}}', ['id' => 37, 'name' => 'มุกดาหาร']);
        $this->insert('{{%address_provice}}', ['id' => 38, 'name' => 'เชียงใหม่']);
        $this->insert('{{%address_provice}}', ['id' => 39, 'name' => 'ลำพูน']);
        $this->insert('{{%address_provice}}', ['id' => 40, 'name' => 'ลำปาง']);
        $this->insert('{{%address_provice}}', ['id' => 41, 'name' => 'อุตรดิตถ์']);
        $this->insert('{{%address_provice}}', ['id' => 42, 'name' => 'แพร่']);
        $this->insert('{{%address_provice}}', ['id' => 43, 'name' => 'น่าน']);
        $this->insert('{{%address_provice}}', ['id' => 44, 'name' => 'พะเยา']);
        $this->insert('{{%address_provice}}', ['id' => 45, 'name' => 'เชียงราย']);
        $this->insert('{{%address_provice}}', ['id' => 46, 'name' => 'แม่ฮ่องสอน']);
        $this->insert('{{%address_provice}}', ['id' => 47, 'name' => 'นครสวรรค์']);
        $this->insert('{{%address_provice}}', ['id' => 48, 'name' => 'อุทัยธานี']);
        $this->insert('{{%address_provice}}', ['id' => 49, 'name' => 'กำแพงเพชร']);
        $this->insert('{{%address_provice}}', ['id' => 50, 'name' => 'ตาก']);
        $this->insert('{{%address_provice}}', ['id' => 51, 'name' => 'สุโขทัย']);
        $this->insert('{{%address_provice}}', ['id' => 52, 'name' => 'พิษณุโลก']);
        $this->insert('{{%address_provice}}', ['id' => 53, 'name' => 'พิจิตร']);
        $this->insert('{{%address_provice}}', ['id' => 54, 'name' => 'เพชรบูรณ์']);
        $this->insert('{{%address_provice}}', ['id' => 55, 'name' => 'ราชบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 56, 'name' => 'กาญจนบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 57, 'name' => 'สุพรรณบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 58, 'name' => 'นครปฐม']);
        $this->insert('{{%address_provice}}', ['id' => 59, 'name' => 'สมุทรสาคร']);
        $this->insert('{{%address_provice}}', ['id' => 60, 'name' => 'สมุทรสงคราม']);
        $this->insert('{{%address_provice}}', ['id' => 61, 'name' => 'เพชรบุรี']);
        $this->insert('{{%address_provice}}', ['id' => 62, 'name' => 'ประจวบคีรีขันธ์']);
        $this->insert('{{%address_provice}}', ['id' => 63, 'name' => 'นครศรีธรรมราช']);
        $this->insert('{{%address_provice}}', ['id' => 64, 'name' => 'กระบี่']);
        $this->insert('{{%address_provice}}', ['id' => 65, 'name' => 'พังงา']);
        $this->insert('{{%address_provice}}', ['id' => 66, 'name' => 'ภูเก็ต']);
        $this->insert('{{%address_provice}}', ['id' => 67, 'name' => 'สุราษฎร์ธานี']);
        $this->insert('{{%address_provice}}', ['id' => 68, 'name' => 'ระนอง']);
        $this->insert('{{%address_provice}}', ['id' => 69, 'name' => 'ชุมพร']);
        $this->insert('{{%address_provice}}', ['id' => 70, 'name' => 'สงขลา']);
        $this->insert('{{%address_provice}}', ['id' => 71, 'name' => 'สตูล']);
        $this->insert('{{%address_provice}}', ['id' => 72, 'name' => 'ตรัง']);
        $this->insert('{{%address_provice}}', ['id' => 73, 'name' => 'พัทลุง']);
        $this->insert('{{%address_provice}}', ['id' => 74, 'name' => 'ปัตตานี']);
        $this->insert('{{%address_provice}}', ['id' => 75, 'name' => 'ยะลา']);
        $this->insert('{{%address_provice}}', ['id' => 76, 'name' => 'นราธิวาส']);
        $this->insert('{{%address_provice}}', ['id' => 77, 'name' => 'บึงกาฬ']);
    }

    public function down()
    {
        $this->dropTable('{{%address_provice}}');
    }
}
