<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'asdfhgjklxbn1234',
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
