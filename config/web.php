<?php

$db = require(__DIR__ . '/db.php');
$modules = require(__DIR__ . '/modules.php');
$params = require(__DIR__ . '/params.php');
$views = require(__DIR__. '/views.php');

$config = [
    'id' => 'thesis',
    'name' => 'Thesis',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Bangkok',
    'language' => 'th-TH',
    'charset' => 'UTF-8',
    'homeUrl' => '/',
    'bootstrap' => ['log'],
    'components' => [
        'cronf' => [
            'class' => 'app\components\Cronf',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'baseUrl' => '/',
            'cookieValidationKey' => 'HV-l1ke_qzLXufzTI2W4ERipoZNELSPR',
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'urlManager' => [
            'baseUrl' => '/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'formatter' => [
            'defaultTimeZone' => 'Asia/Bangkok',
        ],
        'db' => $db,
        'view' => $views,
    ],
    'params' => $params,
    'modules' => $modules,
    'on beforeRequest' => function ($event) {
        Yii::$classMap['app\components\Gridmenu'] = '@app/components/gridmenu.php';
        Yii::$classMap['app\components\Formmod'] = '@app/components/formmod.php';
        Yii::$classMap['app\components\Nofication'] = '@app/components/nofication.php';
        Yii::$classMap['app\components\Language'] = '@app/components/language.php';

        if (Yii::$app->request->get('language'))
            Yii::$app->session->set('language', Yii::$app->request->get('language'));

        if (Yii::$app->session->get('language'))
            Yii::$app->language = Yii::$app->session->get('language');
        
        if (Yii::$app->params['maintenance'])
            Yii::$app->catchAll = ['site/maintenance'];

        Yii::$app->cronf->autoUpdate();
    },
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
