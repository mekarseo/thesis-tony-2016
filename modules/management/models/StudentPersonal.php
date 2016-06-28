<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class StudentPersonal extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_personal_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['personal_id', 'first_name', 'last_name', 'birth_date', 'mobile', 'email'], 'required'],
            [['birth_date'], 'safe'],
            [['address', 'parent_address'], 'string'],
            [['std_id', 'personal_id'], 'string', 'max' => 13],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['mobile', 'father_mobile', 'mother_mobile', 'parent_mobile'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 150],
            [['father', 'mother', 'parent'], 'string', 'max' => 200],
            [['term'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'std_id' => Yii::t('app', 'Student ID'),
            'personal_id' => Yii::t('app', 'Personal ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'father' => Yii::t('app', 'Father'),
            'father_mobile' => Yii::t('app', 'Father Mobile'),
            'mother' => Yii::t('app', 'Mother'),
            'mother_mobile' => Yii::t('app', 'Mother Mobile'),
            'parent' => Yii::t('app', 'Parent'),
            'parent_mobile' => Yii::t('app', 'Parent Mobile'),
            'parent_address' => Yii::t('app', 'Parent Address'),
            'term' => Yii::t('app', 'Term'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEducationInfo()
    {
        return $this->hasOne(UserEducationInfo::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStudentMaps()
    {
        return $this->hasMany(UserStudentMap::className(), ['csv_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTalentInfo()
    {
        return $this->hasOne(UserTalentInfo::className(), ['id' => 'id']);
    }
}
