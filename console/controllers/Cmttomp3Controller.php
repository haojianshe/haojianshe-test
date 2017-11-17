<?php
namespace console\controllers;

use Yii;

/**
 * 评论守护进程
 */
class Cmttomp3Controller extends \yii\console\Controller
{
    public function actions()
    {
        return [
        	//启动进程
        	'start' => [
        		'class' => 'console\controllers\cmttomp3\StartAction',
        	],
        	//结束进程
        	'stop' => [
        		'class' => 'console\controllers\cmttomp3\StopAction',
        	],
        ];
    }
}