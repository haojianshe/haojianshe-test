<?php
namespace api\modules\v2_2_0\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\PosidHomeService;
use api\lib\enumcommon\ReturnCodeEnum;


/**
 * 顶部广告
 */
class TopAdvAction extends ApiBaseAction
{
    public function run()
    {   
        $ret = PosidHomeService::getPosidHomeList(2);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    }
}
