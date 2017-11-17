<?php
namespace api\modules\v2_2_0\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelService;
use api\service\CapacityModelMaterialService;
use api\service\TweetService;

/**
 * 判断用户是否有能力模型图
 */
class HasCapacityAction extends ApiBaseAction
{
    public function run()
    {    	
    	$ret['hascapacity'] = 0;
    	//取能力模型
    	$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 4);
    	if($tmp){
    		$ret['hascapacity'] = 1;
    		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	}
    	$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 1);
    	if($tmp){
    		$ret['hascapacity'] = 1;
    		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	}
    	$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 5);
    	if($tmp){
    		$ret['hascapacity'] = 1;
    		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    	
    }
}
