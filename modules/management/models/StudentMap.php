<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class StudentMap extends ActiveRecord
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
}
