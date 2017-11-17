<?php
namespace api\modules\v3_0_2\controllers\lecture;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use api\service\LectureService;
use api\service\NewsService;
/**
 * 获取专题详情
 * @author ihziluoh
 *
 */
class SubjectDetailAction extends ApiBaseAction{

   public  function run(){
        $newsid=$this->requestParam('newsid',true);
        $uid=$this->_uid;
        //专题基本信息（包括分享）
        $data['subject_info']=LectureService::getLectureInfo($newsid,$uid);
        //专题下标签内容
        $data['tag_list']=LectureService::getSubjectDetailRedis($newsid);
        LectureService::update_hits($newsid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}