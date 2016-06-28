<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\management\models\StudentEducation;
use app\modules\management\models\EduLevel;

class EducationModel extends ActiveRecord
{
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
            [['user_id', 'csv_id'], 'required'],
            [['user_id', 'csv_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'csv_id'  => Yii::t('app', 'Csv ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(StudentEducation::className(), ['id' => 'csv_id']);
    }
}
