<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkCorrectService;

/**
 * 改画列表页
 */
class SubmitListAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_activity';
	
	public function run()
    {
        $request = Yii::$app->request;
        $activityid=$request->get("activityid");
    	//分页获取改画活动列表
    	$data = DkCorrectService::getByPage($activityid);
       // var_dump($data);exit;
    	return $this->controller->render('submitlist',$data);
    }
}
