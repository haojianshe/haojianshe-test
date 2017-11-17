<?php
namespace api\controllers\catalog;

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
        $data= json_decode(DictdataService::getTweetTypeAndTagStr("catalog"),true)['data'];
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
