<?php
namespace api\modules\v3_0_3\controllers\catalog;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use common\service\DictdataService;
/**
 * 得到分类信息
 */
class GetAction extends ApiBaseAction
{   
    public function run()
    {    	
        $data=DictdataService::getTweetType();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
