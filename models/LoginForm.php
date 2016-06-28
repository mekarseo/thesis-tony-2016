<?php
/**
 * @Final File
 */
namespace app\models;

use dektrium\user\models\LoginForm as BaseForm;
use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use Yii;

class LoginForm extends BaseForm
{
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('app', 'Username'),
            'password'   => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember me next time'),
        ];
    }

    public function rules()
    {
        return [
            'requiredFields'    => [['login', 'password'], 'required'],
            'loginTrim'         => ['login', 'trim'],
            'passwordValidate'  => [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('app', 'Invalid Username or Password'));
                    }
                }
            ],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('app', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('app', 'Your account has been blocked'));
                        }
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];
    }
}