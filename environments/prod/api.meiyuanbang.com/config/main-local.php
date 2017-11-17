<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'asdfhgjklxbn1234',
        ],
   		//用来缓存需要后台离线处理的数据
   		'cachequeue' => [
			'class' => 'common\redis\Cache',
  			'redis' => [
				'hostname' => 'myb-ol-back2',
				'port' => 8888,
   			]
    	],
    	'db' => [
   			'class' => 'yii\db\Connection',
   			'dsn' => 'mysql:host=rdsa3ztcm25jcbq937ryh.mysql.rds.aliyuncs.com;dbname=myb',
   			'username' => 'myb',
   			'password' => 'MhxzKhl',
   			'charset' => 'utf8mb4',
    	],
    ],
];
