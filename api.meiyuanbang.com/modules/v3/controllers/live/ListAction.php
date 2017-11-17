<?php
namespace api\modules\v3\controllers\live;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;

/**
 * 直播列表
 * @author ihziluoh
 *
 */
class ListAction extends ApiBaseAction{
   public  function run(){
        $rn = $this->requestParam('rn');
        if(!$rn){
            $rn = 10;
        }
        $lastid = $this->requestParam('lastid');
        $uid=$this->_uid;
        //缓存获取直播id列表
        $liveids=LiveService::getLiveList($lastid,$rn);
        //获取直播列表详情
        $ret=LiveService::getListDetail($liveids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}