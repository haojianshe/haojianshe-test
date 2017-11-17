<?php
namespace console\controllers;

use Yii;

/**
 * 课程守护进程
 */
class CourseController extends \yii\console\Controller
{
    public function actions()
    {
        return [
        	//临时更改价格
        	'update_price_temp' => [
        		'class' => 'console\controllers\course\UpdatePriceTempAction',
        	],
       		//预发放课程卷检查程序
       		'double11' => [
   				'class' => 'console\controllers\course\Double11Action'
       		]
        	
        ];
    }
}