<?php
namespace api\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 活动列表页
 */
class RegisterUserAction extends ApiBaseAction
{
	public function run()
    {
    	//测试detail
    	$ret = UserDetailService::getByUid($this->_uid);
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
