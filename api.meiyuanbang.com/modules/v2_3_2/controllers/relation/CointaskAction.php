<?php
namespace api\modules\v2_3_2\controllers\relation;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\CointaskService;
use api\service\UserCoinService;

/**
 *  
 * 关注成功加积分
 */
class CointaskAction extends ApiBaseAction
{
	public function run()
    {
        $request = Yii::$app->request;
        $tasktype = CointaskTypeEnum::FOLLOW;
        $data = [];
        
        //关注成功后检查积分
        if(CointaskService::IsAddByDaily($this->_uid, $tasktype)){
        	//需要加金币
        	$coinCount = CointaskDictService::getCoinCount($tasktype);
        	UserCoinService::addCoinNew($this->_uid, $coinCount);
        	$data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }  
}