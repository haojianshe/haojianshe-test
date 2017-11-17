<?php
namespace api\modules\v2_2_0\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\CapacityModelDictDataService;

/**
 * 获取能力模型图所有打分项
 */
class ItemListAction extends ApiBaseAction
{
    public function run()
    {   
    	//根据主类型id和分类型id必须传，分类型id目前只是备用
    	$maintypeid = $this->requestParam('maintypeid',true);
    	$subtypeid = $this->requestParam('subtypeid',true);
    	
    	$ret = CapacityModelDictDataService::getCorrectScoreItemByMainId($maintypeid);
    	if(!$ret){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    	
    }
}
