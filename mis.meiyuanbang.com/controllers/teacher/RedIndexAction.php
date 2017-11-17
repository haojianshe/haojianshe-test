<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCorrectService;
use common\models\myb\UserCorrect;

/**
 * 红笔老师列表页
 */
class RedIndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_teacher';
	
	public function run()
    {
    	//获取红笔老师列表
    	$request = Yii::$app->request;
        $search['pay_type'] = trim($request->get("pay_type")); #主分类
       	
    	$data =  UserCorrectService::getByPage($search['pay_type']);
    	$data['search']=$search;
    	return $this->controller->render('red',$data);
    }
}
