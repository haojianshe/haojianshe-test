<?php
namespace api\modules\v2_2_0\controllers\favorite;

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
        $type=$this->requestParam('type',true);
    	//查找
    	$model = FavoriteService::findOne(['uid' => $this->_uid,'tid'=>$tid,'type'=>$type]);
    	if($model){
    		$model->delete();
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK); 
    }
}