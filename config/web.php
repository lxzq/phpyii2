<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'User Email system',
   // 'path'=>'http://'.$_SERVER['HTTP_HOST'],,
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=>'admin/index/index',//默认路由，控制器+方法
    'modules' => [
        'admin'=>'app\modules\admin',//后台模块引用
    ],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dFtcrAVWaX_8_fOYsktXimlr9EvR1kzr',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //路由管理
            'rules' => [
                "<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>"=>"<module>/<controller>/<action>",
                "<controller:\w+>/<action:\w+>/<id:\d+>"=>"<controller>/<action>",
                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>",
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',//模型自动登录
            'enableAutoLogin' => true,
            'loginUrl'=>['admin/index/login'],//定义后台默认登录界面[权限不足跳到该页]
        ],
        'errorHandler' => [
            'errorAction' => 'public/error',//默认错误处理页
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'ykt' => require(__DIR__ . '/ykt.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
