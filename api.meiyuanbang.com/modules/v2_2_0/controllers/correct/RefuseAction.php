<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\TweetService;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 拒批
 */
class RefuseAction extends ApiBaseAction
{
    public function run()
    {   
    	$correctid=$this->requestParam('correctid',true);
    	$reasonid=$this->requestParam('reasonid',true);
    	//获取数据库中记录
    	$modelCorrect =  CorrectService::findOne(['correctid' => $correctid]);
    	//批改相关的老师才能拒绝批改
    	if($modelCorrect->teacheruid != $this->_uid){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
    	}
    	$tid = $modelCorrect['tid'];
    	//把帖子变为删除状态
    	$modelTweet = TweetService::findOne(['tid' => $tid]);
    	$modelTweet->is_del=1;
    	$modelTweet->save();
    	//把批改变为拒批状态
    	$modelCorrect->status = 3;
    	$modelCorrect->refuse_reasonid = $reasonid;
    	$modelCorrect->save();
    	//把老师待批改数-1
    	$modelTeacher = UserCorrectService::findOne(['uid'=>$modelCorrect->teacheruid]);
    	if($modelTeacher->queuenum>0){
    		$modelTeacher->queuenum= $modelTeacher->queuenum-1;
    		$modelTeacher->save();
    	}    	
    	//给学生推送小红点
    	CorrectService::refusePushMsg($this->_uid,$modelCorrect->submituid,$correctid);
    	
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);    	
    }
}
