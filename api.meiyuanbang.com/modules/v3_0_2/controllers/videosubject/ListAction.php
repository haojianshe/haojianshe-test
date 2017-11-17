<?php
namespace api\modules\v3_0_2\controllers\videosubject;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\VideoSubjectService;

/**
 * 课程专题列表
 * @author ihziluoh
 *
 */
class ListAction extends ApiBaseAction{
   public  function run(){
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
        $lastid=$this->requestParam('lastid');
        //获取课程专题列表信息
        $videosubjectids=VideoSubjectService::getVideoSubjectList($lastid,$rn);
        $data=VideoSubjectService::getVideoSubjectListInfo($videosubjectids);

        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}