<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-mis',
    'basePath' => dirname(__DIR__),
	'defaultRoute' => 'index',
    'controllerNamespace' => 'mis\controllers',
    'bootstrap' => ['log'],
    'modules' => [],	
    'components' => [
        'user' => [
            'identityClass' => 'mis\service\MisUserService',
            'enableAutoLogin' => true,
        	'loginUrl' => '/',
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
