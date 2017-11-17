<?php
namespace api\modules\v3\controllers\user;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ScanVideoRecordService;

/**
 * 个人中心直播列表
 * @author ihziluoh
 *
 */
class ScanVideoDelAction extends ApiBaseAction{
   public  function run(){
      
        $uid = $this->_uid;
        $recordid = $this->requestParam('recordid',true);
        $ret=ScanVideoRecordService::delScanVideoRecord($uid,$recordid);
        if($ret){
            return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            return $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        }
        
        
    }
}