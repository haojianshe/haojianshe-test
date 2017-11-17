<?php
namespace api\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 取消收藏
 */
class CancelAction extends ApiBaseAction
{
	public function run()
    {    	
    	$tid =$this->requestParam('tid',true);
    	//添加记录
    	$model = FavoriteService::findOne(['uid' => $this->_uid,'tid'=>$tid]);
    	if($model){
    		$model->delete();
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK); 
    }
}