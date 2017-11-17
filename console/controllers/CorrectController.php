<?php
namespace console\controllers;

use Yii;

/**
 * 批改分享守护进程
 */
class CorrectController extends \yii\console\Controller
{
    public function actions()
    {
        return [
        	//启动进程
        	'start' => [
        		'class' => 'console\controllers\correcttask\StartAction',
        	],
        	//结束进程
        	'stop' => [
        		'class' => 'console\controllers\correcttask\StopAction',
        	],
        ];
    }
}