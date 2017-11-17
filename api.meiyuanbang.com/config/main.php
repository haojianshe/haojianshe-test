<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'index',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1_2' => [
            'class' => 'api\modules\v1_2\Module'
        ],
        'v1_3' => [
            'class' => 'api\modules\v1_3\Module'
        ],
        'v2_0_1' => [
            'class' => 'api\modules\v2_0_1\Module'
        ],
        'v2_2_0' => [
            'class' => 'api\modules\v2_2_0\Module'
        ],
        'v2_3_2' => [
            'class' => 'api\modules\v2_3_2\Module'
        ],
        'v2_3_3' => [
            'class' => 'api\modules\v2_3_3\Module'
        ],
        'v2_3_5' => [
            'class' => 'api\modules\v2_3_5\Module'
        ],
        'v2_3_7' => [
            'class' => 'api\modules\v2_3_7\Module'
        ],
        'v2_4_2' => [
            'class' => 'api\modules\v2_4_2\Module'
        ],
        'v3' => [
            'class' => 'api\modules\v3\Module'
        ],
        #画室接口
        'v3_0_1' => [
            'class' => 'api\modules\v3_0_1\Module'
        ],
        'v3_0_2' => [
            'class' => 'api\modules\v3_0_2\Module'
        ],
        'v3_0_3' => [
            'class' => 'api\modules\v3_0_3\Module'
        ],
        'v3_0_4' => [
            'class' => 'api\modules\v3_0_4\Module'
        ],
        'v3_1_1' => [
            'class' => 'api\modules\v3_1_1\Module'
        ],
        'v3_2_1' => [
            'class' => 'api\modules\v3_2_1\Module'
        ],
        //收藏夹接口
        'v3_2_3' => [
            'class' => 'api\modules\v3_2_3\Module'
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\service\MisUserService',
            'enableAutoLogin' => true,
            'loginUrl' => '/',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //路由管理
            'rules' => [
                "<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>" => "<module>/<controller>/<action>",
                "<controller:\w+>/<action:\w+>/<id:\d+>" => "<controller>/<action>",
                "<controller:\w+>/<action:\w+>" => "<controller>/<action>",
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    //不记录各种服务器变量
                    'logVars' => [''],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
