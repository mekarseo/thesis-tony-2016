<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;

class AccountModel extends ActiveRecord
{
    /**
     * @property
     */
    public $old_password;
    public $new_password1;
    public $new_password2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_password', 'email'], 'required'],
            [['old_password', 'email', 'new_password1', 'new_password2'], 'trim'],
            [['new_password1', 'new_password2'], 'string', 'length' => [8, 16]],
            ['email', 'email'],
            ['new_password2', 'compare', 'compareAttribute' => 'new_password1', 'skipOnEmpty' => false, 'message' => Yii::t('app', 'Password not match, Plase try again.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'             => Yii::t('app', 'Email'),
            'old_password'      => Yii::t('app', 'Old Password'),
            'new_password1'     => Yii::t('app', 'New Password'),
            'new_password2'     => Yii::t('app', 'Password Again'),
        ];
    }

    /**
     * @return yii\db\Command
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        $password = Yii::$app->security->generatePasswordHash($this->new_password1, Yii::$app->getModule('user')->cost);
        $registration_ip = Yii::$app->request->userIP;
        $updated_at = time();

        if (empty($this->new_password1)) {
            $result = Yii::$app->db->createCommand("UPDATE {{%user}} SET `email` = '" . $this->email . "', `registration_ip` = '" . $registration_ip . "', `updated_at` = '" . $updated_at . "' WHERE `id` = " . (int)$this->id . ";")->execute();
        } else {
            $result = Yii::$app->db->createCommand("UPDATE {{%user}} SET `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `registration_ip` = '" . $registration_ip . "', `updated_at` = '" . $updated_at . "' WHERE `id` = " . (int)$this->id . ";")->execute();
        }

        Yii::$app->db->createCommand("UPDATE {{%profile}} SET `public_email` = '" . $this->email . "' WHERE `user_id` = " . (int)$this->id . ";")->execute();

        return (bool)$result;
    }

    /**
     * @return yii\db\Command
     */
    public function passwordCheck()
    {
        $result = Yii::$app->db->createCommand("SELECT * FROM {{%user}} WHERE `id` = '" . (int)$this->id. "';")->queryOne();

        return Yii::$app->security->validatePassword($this->old_password, $result['password_hash']);
    }
}
