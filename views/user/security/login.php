<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                   $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module           $module
 */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-lock"></i> <?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'login-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                    'validateOnType'         => false,
                    'validateOnChange'       => false,
                ]) ?>

                <?= $form->field($model, 'login', ['template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-user\"></i></span>\n{input}\n</div>\n{hint}\n{error}"])->textInput(['class' => 'form-control', 'placeholder' => Yii::t('app', 'Username'), 'value' => '']) ?>

                <?= $form->field($model, 'password', ['template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-lock\"></i></span>\n{input}\n</div>\n{hint}\n{error}"])->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('app', 'Password')])->label(Yii::t('app', 'Password') . ($module->enablePasswordRecovery ? ' (' . Html::a(Yii::t('app', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')' : '')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('app', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
            </p>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('app', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
            </p>
        <?php endif ?>
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>
        <div class="text-center">KMUT'NB © 2016 All Rights Reserved.</div>
    </div>

</div>
