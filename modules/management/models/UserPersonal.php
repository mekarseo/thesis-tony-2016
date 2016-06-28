<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;

class UserPersonal extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_personal_info}}';
    }
}
