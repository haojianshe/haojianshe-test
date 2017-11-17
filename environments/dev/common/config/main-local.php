<?php
return [
	'components' => [
	    //缓存
		'cache' => [
			'class' => 'common\redis\Cache',
			'redis' => [
				'hostname' => '192.168.1.13',
				'port' => 8889,
			]
		],
	],
];
