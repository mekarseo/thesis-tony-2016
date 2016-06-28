<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class EduLevel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%edu_level}}';
    }
}
