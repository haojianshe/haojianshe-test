<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkModulesService;

/**
 * 改画模块列表页
 */
class ModelsAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_activity';
	
	public function run()
    {
         $request = Yii::$app->request;
        $activityid = $request->get('activityid'); 
    	//分页获取改画活动列表
    	$data = DkModulesService::getByPage($activityid);
        $data['activityid']=$activityid;
    	return $this->controller->render('modules',$data);
    }
}
