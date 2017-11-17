<?php
namespace api\modules\v3_0_2\controllers\lecture;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use api\service\LectureService;
/**
 * 获取精讲文章详情
 * @author ihziluoh
 *
 */
class GetInfoAction extends ApiBaseAction{

   public  function run(){
        $newsid=$this->requestParam('newsid',true);
        $uid=$this->_uid;
        //精讲文章基本信息（包括分享）
        $data=LectureService::getLectureInfo($newsid,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}