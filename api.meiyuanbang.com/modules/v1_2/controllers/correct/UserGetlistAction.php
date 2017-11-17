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
 * 学生批改列表
 */
class UserGetlistAction extends ApiBaseAction
{
    public function run()
    {
        $request=Yii::$app->request;
        $redis = Yii::$app->cache;
        $submituid=$this->_uid;
        $lastid=$this->requestParam('lastid');
        $rn=$this->requestParam('rn');
        if(!isset($rn)){
            $rn=10;
        }
        //清除小红点       
        CorrectService::clearRedCorrectNum($this->_uid);
        $correctids=CorrectService::getUserCorrectList($submituid,$lastid,$rn);
        //判断返回是否是空数组
        if(count($correctids)<1){
            $data['content']=array();
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        //获取图片语音
        foreach ($correctids as $key => $value) {
            $correct_info=CorrectService::getCorrectDetail($value);
            if(!empty($correct_info['source_pic_rid'])){
            //原图
                $correct_info['source_pic']=ResourceService::getResourceDetail($correct_info['source_pic_rid']);
            }else{
                $correct_info['source_pic']=(object)null;
            }

            if(!empty($correct_info['correct_pic_rid'])){
            //批改后图片
                $correct_info['correct_pic']=ResourceService::getResourceDetail($correct_info['correct_pic_rid']);
            }else{
                $correct_info['correct_pic']=(object)null;
            } 
            $data_content[]=$correct_info;
        }
        $data['content']=$data_content;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
