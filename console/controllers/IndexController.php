<?php
namespace console\controllers;

use Yii;

/**
 * 推送守护进程
 */
class IndexController extends \yii\console\Controller
{
	/**
     *相关的action集合 
     */
    public function actions()
    {
        return [
        	//启动进程
        	'start' => [
        		'class' => 'console\controllers\index\StartAction',
        	],
        	//结束进程
        	'stop' => [
        		'class' => 'console\controllers\index\StopAction',
        	],
        ];
    }
}