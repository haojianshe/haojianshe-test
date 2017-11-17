<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\TeamInfoService;
use mis\service\TeamMemberService;

/**
 * 认证老师设置为殿堂功能
 */
class FamousAddAction extends MBaseAction
{	
	public $resource_id = 'operation_teacher';
	
    /**
     * 只支持post删除
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$userid = $request->post('userid');
    	if(!$userid || !is_numeric($userid)){
    		die('参数不正确');
    	}
    	//判断用户是否为殿堂老师
    	$model = UserService::findOne(['uid'=>$userid]);
    	if($model->ukind!=1){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户的身份还不是认证老师！']);
    	}
    	if($model->ukind_verify==1){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户已经是殿堂老师！']);
    	}
    	//判断用户以前是否已经添加过小组信息
    	$teammodel = TeamInfoService::findOne(['uid'=>$userid]);
    	if(!$teammodel){
    		//第一次设置为殿堂老师需要添加小组初始数据
    		$teammodel = new TeamInfoService();
    		$teammodel->uid =$userid;
    		$teammodel->teamname = $model->sname . '的小组';
    		$teammodel->membercount = 1;
    		$teammodel->ctime =time();
    		$teammodel->save();
            //自己加入小组
            $teammember=new TeamMemberService();
            $teammember->teamid=$teammodel->attributes['teamid'];
            $teammember->uid=$userid;
            $teammember->addtime=time();
            $teammember->isadmin=2;
            $teammember->save();
    	}    	
    	//把用户设置为殿堂老师
    	$model->ukind_verify = 1;
    	if($model->save()){
    		//清除殿堂老师和用户信息缓存
    		UserService::removecache($userid);
    		UserService::remove_famousteacher_cache();
    		return $this->controller->outputMessage(['errno'=>0]);
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    	}
    }
}
