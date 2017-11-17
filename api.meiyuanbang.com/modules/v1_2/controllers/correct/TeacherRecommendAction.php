<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;

/**
 * 推荐批改老师
 */
class TeacherRecommendAction extends ApiBaseAction
{
    public function run()
    {       
        //随机获取一个推荐老师id
        $teacherid = UserCorrectService::getRecommendId();
        $data = UserCorrectService::getUserCorrectDetail($teacherid);    
        $data = array_merge(UserDetailService::getByUid($teacherid),$data);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
