<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\TweetService;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 删除被驳回的求批改
 */
class DelAction extends ApiBaseAction
{
    public function run()
    {       
    	$correctid=$this->requestParam('correctid',true);
    	//获取数据库中记录
    	$modelCorrect =  CorrectService::findOne(['correctid' => $correctid]);
    	//学生只能删除自己的求批改
    	if($modelCorrect->submituid != $this->_uid){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
    	}
    	//把批改变为删除状态
    	$modelCorrect->status = 2;
    	$modelCorrect->save();
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);  
    }
}
