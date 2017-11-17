<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\CorrectService;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\CommentService;

/**
 * 后台帖子列表页
 * 
 */
class UserRelationAction extends MBaseAction
{
  public $resource_id = 'operation_stat';

	public function run()
    {
    	$request = Yii::$app->request;
    	$is_search=$request->get("is_search")?$request->get("is_search"):0;
    	//开始结束时间
    	$con['start_time']=$request->get("start_time")?$request->get("start_time"):date("Y-m-d 00:00:00",strtotime("-1 month"));
    	$con['end_time']=$request->get("end_time")?$request->get("end_time"):date('Y-m-d 00:00:00',strtotime("+1 day"));
    	//查询用户关系
		$data=CorrectService::getCorrectUserRelation(strtotime($con['start_time']),strtotime($con['end_time']));
		//获取用户信息
		foreach ($data['models'] as $key=>$value){
			$data['models'][$key]['submit']=UserService::getInfoByUids($value['submituid']);
			$data['models'][$key]['teacher']=UserService::getInfoByUids($value['teacheruid']);
		}
		//判断是否搜索
		$con['is_search']=$is_search;
		//返回搜索条件
		$data['search']=$con;
        return $this->controller->render('userrelation', $data);
		
       }
}
