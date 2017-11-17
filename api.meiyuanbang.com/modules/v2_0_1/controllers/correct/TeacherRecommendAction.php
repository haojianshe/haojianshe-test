<?php
namespace api\modules\v2_0_1\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;

/**
 * 推荐多个批改老师
 */
class TeacherRecommendAction extends ApiBaseAction
{
    public function run()
    {       
        $maintype=$this->requestParam('maintype') ? $this->requestParam('maintype'): 0;
    	$num = $this->requestParam('num',true);
        //随机获取一个推荐老师id
        $teacherids = UserCorrectService::getRecommendIds($maintype,$num);
        $ret = [];
        foreach ($teacherids as $k=>$teacherid){
        	$data = UserCorrectService::getUserCorrectDetail($teacherid);
        	$data = array_merge(UserDetailService::getByUid($teacherid),$data);
        	$ret[] = $data;
        }        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
