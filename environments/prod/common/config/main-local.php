<?php
return [
    'components' => [
	    'cache' => [
			'class' => 'common\redis\Cache',
			'redis' => [
				'hostname' => 'myb-ol-back1',
				'port' => 8889,
			]
		],
	],
];
