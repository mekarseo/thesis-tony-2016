<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class UserTalent extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_talent_info}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'talent_type'   => Yii::t('app', 'Talent Type'),
            'talent_sub'    => Yii::t('app', 'Talent Sub'),
            'talent_sub1'   => Yii::t('app', 'Talent Sub1'),
            'talent_sub2'   => Yii::t('app', 'Talent Sub2'),
            'talent_honor'  => Yii::t('app', 'Talent Honor'),
        ];
    }
}
