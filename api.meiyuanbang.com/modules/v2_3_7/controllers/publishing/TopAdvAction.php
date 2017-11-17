<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\PosidHomeUserService;

/**
 * 出版社个人中心顶部广告
 */
class TopAdvAction extends ApiBaseAction
{
    public function run()
    {   
    	$uid = $this->requestParam('uid',true); 
    	$version = $this->requestParam('version'); 
    	if(intval($version)>300){
    		$data=PosidHomeUserService::getPublishingTopAdv($uid);
    	}else{
    		$data=[];
    	}
        
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
