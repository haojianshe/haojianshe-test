<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use common\redis\Cache;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\ResourceService;
use common\service\CommonFuncService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 已批改列表
 */
class TeacherHasCorrectAction extends ApiBaseAction
{
    public function run()
    {
        $redis = Yii::$app->cache;
        $teacheruid=$this->_uid;
        $lastid=$this->requestParam('lastid');
        $rn=$this->requestParam('rn');
        if(!isset($rn)){
            $rn=10;
        }
        //清除小红点       
        CorrectService::clearRedCorrectNum($this->_uid);
        $correctids=CorrectService::getTeacherCorrectidsByStatus($this->_uid,1,$lastid,$rn);
        if(count($correctids)<1){
            $data['content']=array();
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }       
        //获取图片语音
        foreach ($correctids as $key => $value) {
            $data_content[]=CorrectService::getListDetailInfo($value['correctid']);
        }
        $data['content']=$data_content;
        $data['total_count']=CorrectService::getTeacherCorrectCount($teacheruid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
