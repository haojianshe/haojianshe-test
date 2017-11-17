<?php
namespace api\modules\v1_3\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\TweetService;

/**
 * 获取素材总数
 */
class TotalNumAction extends ApiBaseAction
{
	public function run()
    {
    	$ret = TweetService::getMaterialNum();
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
