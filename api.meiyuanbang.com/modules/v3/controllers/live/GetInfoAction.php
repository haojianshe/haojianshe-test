<?php
namespace api\modules\v3\controllers\live;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;
use common\lib\myb\enumcommon;
use common\service\myb\InfocollectionVisitService;
use common\lib\myb\enumcommon\InfoCollectionSubjectTypeEnum;

/**
 * 直播详情
 * @author ihziluoh
 *
 */
class GetInfoAction extends ApiBaseAction{
   public  function run(){
        //直播id
        $liveid = $this->requestParam('liveid',true);
        $uid=$this->_uid;
        $detail=LiveService::getDetail($liveid,$uid);
        //指定用户记录直播被访问信息
        InfocollectionVisitService::writeVisitRecord($this->_uid, $detail['teacheruid'], InfoCollectionSubjectTypeEnum::LIVE);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$detail);
    }
}