<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\management\models\StudentPersonal;

class ProfileModel extends ActiveRecord
{
    /**
     * @property
     */
    public $mobile;
    public $address;
    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_student_map}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'csv_id', 'mobile', 'password'], 'required'],
            [['user_id', 'csv_id'], 'integer'],
            [['address'], 'string'],
            [['password'], 'string', 'length' => [8, 16]],
            [['mobile'], 'string', 'length' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id'   => Yii::t('app', 'User ID'),
            'csv_id'    => Yii::t('app', 'Csv ID'),
            'password'  => Yii::t('app', 'Password'),
        ];
    }

    /**
     * @return yii\db\Command
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        $result = Yii::$app->db->createCommand("UPDATE {{%user_personal_info}} SET `mobile` = '" . $this->mobile . "', `address` = '" . $this->address . "' WHERE `id` = " . (int)$this->csv_id . ";")->execute();

        return (bool)$result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(StudentPersonal::className(), ['id' => 'csv_id']);
    }

    /**
     * @return yii\db\Command
     */
    public function passwordCheck()
    {
        $result = Yii::$app->db->createCommand("SELECT * FROM {{%user}} WHERE `id` = '" . (int)$this->user_id. "';")->queryOne();

        return Yii::$app->security->validatePassword($this->password, $result['password_hash']);
    }
}
