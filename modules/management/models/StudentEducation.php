<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\management\models\EduLevel;

class StudentEducation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_education_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'level'], 'integer'],
            [['old_school', 'branch'], 'string'],
            [['gpa_graduation'], 'number'],
            [['school_provice'], 'string', 'max' => 100],
            [['graduate'], 'string', 'max' => 200],
            [['major', 'faculty'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'old_school' => Yii::t('app', 'Old School'),
            'school_provice' => Yii::t('app', 'School Provice'),
            'branch' => Yii::t('app', 'Branch'),
            'graduate' => Yii::t('app', 'Graduate'),
            'gpa_graduation' => Yii::t('app', 'Gpa Graduation'),
            'level' => Yii::t('app', 'Level Name'),
            'major' => Yii::t('app', 'Major'),
            'faculty' => Yii::t('app', 'Faculty'),
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
