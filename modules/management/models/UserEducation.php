<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\management\models\EduLevel;

class UserEducation extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_education_info}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'old_school'        => Yii::t('app', 'Old School'),
            'school_provice'    => Yii::t('app', 'School Provice'),
            'branch'            => Yii::t('app', 'Branch'),
            'graduate'          => Yii::t('app', 'Graduate'),
            'gpa_graduation'    => Yii::t('app', 'Gpa Graduation'),
            'level'             => Yii::t('app', 'Level'),
            'faculty'           => Yii::t('app', 'Faculty'),
            'major'             => Yii::t('app', 'Major'),
        ];
    }

   	/**
     * @return \yii\db\ActiveQuery
     */
    public function getLevelName()
    {
        return $this->hasOne(EduLevel::className(), ['id' => 'level']);
    }
}
