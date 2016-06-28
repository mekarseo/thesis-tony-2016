<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class StudentTalent extends ActiveRecord
{
    /**
     * @property
     */
    public $talent_sub1;
    public $talent_sub2;
    public $talent_honor;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_talent_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['talent_detail'], 'string'],
            [['talent_type', 'talent_sub'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'talent_type'   => Yii::t('app', 'Talent Type'),
            'talent_sub'    => Yii::t('app', 'Talent Sub'),
            'talent_detail' => Yii::t('app', 'Talent Detail'),
            'talent_sub1'   => Yii::t('app', 'Talent Sub1'),
            'talent_sub2'   => Yii::t('app', 'Talent Sub2'),
            'talent_honor'  => Yii::t('app', 'Talent Honor'),
        ];
    }
}
