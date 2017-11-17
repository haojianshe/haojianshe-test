<?php
namespace api\modules\v3\controllers\user;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;

/**
 * 个人中心直播列表
 * @author ihziluoh
 *
 */
class LiveListAction extends ApiBaseAction{
   public  function run(){
        $rn = $this->requestParam('rn');
        if(!$rn){
            $rn = 10;
        }
        $uid = $this->requestParam('uid',true);
        $lastid = $this->requestParam('lastid');
        $liveids=LiveService::getTeacherLiveList($uid,$lastid,$rn); 
        $ret=LiveService::getListDetail($liveids); 
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}