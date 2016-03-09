<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3pEyfTxrsOJv-vflXTGdEiEfzLKUIFpV',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => $params['mailer.from'],
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/register'                     => 'auth/register',
                '/login'                        => 'auth/login',
                '/logout'                       => 'auth/logout',
                '/register/confirm/<code:.+>'   => 'auth/confirm',
                '/admin/user/<action>/<id:\d+>' => 'admin/user/<action>'
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'user'],
        ],
    ],
    'as access' => [
        'class' => yii\filters\AccessControl::className(),
        'rules' => [
            [
                'allow' => true,
                'controllers' => ['auth'],
                'roles' => ['?'],
            ],
            [
                'allow' => true,
                'controllers' => ['admin/user'],
                'roles' => ['admin'],
            ],
            [
                'allow' => false,
                'controllers' => ['admin/user'],
                'roles' => ['@'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ]
        ],
    ],
    'on afterRegisterUser' => [
       'app\events\AddAccountListener','onAfterRegisterUser'
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
