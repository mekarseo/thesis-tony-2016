<?php
/**
 * @Final File
 */
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\components\Language;

NavBar::begin([
        'brandLabel' => 'KDSSTSC' . Language::widget([
            ['label' => 'En', 'language' => 'en-US', 'title' => 'English'],
            ['label' => 'Th', 'language' => 'th-TH', 'title' => 'Thai'],
        ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
        !Yii::$app->user->identity->getIsAdmin() ? '' :
            ['label' => Yii::t('app', 'Admin Panel'), 'url' => ['/apn/'], 'active' => Yii::$app->controller->module->id == 'apn' ? true : false],
        !Yii::$app->user->can('AccessManagement') ? '' :
            ['label' => Yii::t('app', 'Management'), 'url' => ['/management/'], 'active' => Yii::$app->controller->module->id == 'management' ? true : false],
        !Yii::$app->user->can('AccessReport') ? '' :
            ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports/'], 'active' => Yii::$app->controller->module->id == 'reports' ? true : false],
            ['label' => Yii::t('app', 'My Account'), 'url' => ['/account/'], 'active' => Yii::$app->controller->module->id == 'account' ? true : false],
            ['label' => Yii::t('app', 'Logout') . ' ('. Yii::$app->user->identity->username . ')', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],
        ],
    ]);

NavBar::end();