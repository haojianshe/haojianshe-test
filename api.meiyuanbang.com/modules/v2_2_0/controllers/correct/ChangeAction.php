<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\TweetService;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 批改转作品
 */
class ChangeAction extends ApiBaseAction
{
    public function run()
    {       
    	$correctid=$this->requestParam('correctid',true);
    	$reasonid=$this->requestParam('reasonid');
    	//获取数据库中记录
    	$modelCorrect =  CorrectService::findOne(['correctid' => $correctid]);
    	//批改老师或者批改学生才能转作品
    	if($modelCorrect->teacheruid != $this->_uid && $modelCorrect->submituid != $this->_uid){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
    	}
    	//把帖子变为作品
    	$tid = $modelCorrect['tid'];
    	$modelTweet = TweetService::findOne(['tid' => $tid]);
    	$modelTweet->type=2;
    	$modelTweet->is_del=0;
    	$modelTweet->save();
    	//把批改变为删除状态
    	$modelCorrect->status = 2;
    	if($reasonid){
    		$modelCorrect->refuse_reasonid = $reasonid;
    	}    	
    	$modelCorrect->save();
    	//老师改作品则待批改数-1,学生转作品则不用修改，因为已经改过
    	if($modelCorrect->teacheruid == $this->_uid){
    		$modelTeacher = UserCorrectService::findOne(['uid'=>$modelCorrect->teacheruid]);
    		if($modelTeacher->queuenum>0){
    			$modelTeacher->queuenum= $modelTeacher->queuenum-1;
    			$modelTeacher->save();
    		}    		
    		//给学生推送小红点
    		CorrectService::changePushMsg($this->_uid,$modelCorrect->submituid,$correctid);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);  
    }
}
