<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
	'components' => [
		//用来缓存需要后台离线处理的数据
		'cachequeue' => [
			'class' => 'common\redis\Cache',
			'redis' => [
				'hostname' => '192.168.1.14',
				'port' => 8889,
			]
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=192.168.1.14;dbname=myb',
			'username' => 'root',
			'password' => 'myb123',
			'charset' => 'utf8mb4',
		],
	],
];
