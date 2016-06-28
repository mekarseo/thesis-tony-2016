<?php

return [
	'user' => [
        'class' => 'dektrium\user\Module',
        'enableRegistration' => false,
        'enableConfirmation' => false,
        'enableUnconfirmedLogin' => true,
        'cost' => 12,
        'admins' => ['admin'],
        'modelMap' => [
            'LoginForm' => 'app\models\LoginForm',
        ],
    ],
    'rbac' => [
        'class' => 'dektrium\rbac\Module',
    ],
    'apn' => [
        'class' => 'app\modules\apn\Module',
    ],
    'management' => [
        'class' => 'app\modules\management\Module',
    ],
    'reports' => [
        'class' => 'app\modules\reports\Module',
    ],
    'account' => [
        'class' => 'app\modules\account\Module',
    ],
];