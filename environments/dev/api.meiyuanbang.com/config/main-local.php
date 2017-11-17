<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'asdfhgjklxbn1234',
        ],
    	'db' => [
   			'class' => 'yii\db\Connection',
   			'dsn' => 'mysql:host=192.168.1.14;dbname=myb',
   			'username' => 'root',
   			'password' => 'myb123',
   			'charset' => 'utf8mb4',
    	],
   		//用来缓存需要后台离线处理的数据
   		'cachequeue' => [
			'class' => 'common\redis\Cache',
   				'redis' => [
					'hostname' => '192.168.1.14',
					'port' => 8889,
   				]
   		],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
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
