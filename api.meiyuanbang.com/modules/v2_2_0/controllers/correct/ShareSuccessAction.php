<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\CorrectShareTaskService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 批改分享成功转换语音
 */
class ShareSuccessAction extends ApiBaseAction
{
    public function run()
    {       
    	$correctid=$this->requestParam('correctid',true);
    	//判断是否已经转过mp3
    	$model= CorrectShareTaskService::findOne(['correctid'=>$correctid]);
    	if($model && $model->issuccess){
    		//已进行过转化
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    	}
    	else{
    		//确认correctid有效
    		$correctmodel = CorrectService::getCorrectDetail($correctid);
    		if(!$correctmodel || $correctmodel['status'] !=1){
    			//correctid无效 或者不是批改状态则返回
    			$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    		}
    		//保存任务
    		if(!$model){
    			$model = new CorrectShareTaskService();
    			$model->correctid = $correctid;
    		}
    		$model->issuccess = 0;
    		$model->ischange = 0;
    		$model->sharetime = time();
    		$model->save();
    		//写cache
    		CorrectService::shareTaskCache($correctid);
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    	}    	 
    }
}
