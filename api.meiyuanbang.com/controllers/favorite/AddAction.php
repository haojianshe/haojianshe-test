<?php
namespace api\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 收藏
 */
class AddAction extends ApiBaseAction
{
	public function run()
    {
    	$tid =$this->requestParam('tid',true);
    	//判断是否已经收藏
    	$model = FavoriteService::findOne(['uid' => $this->_uid,'tid'=>$tid]);
    	if($model){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    	}
    	//添加记录
    	$model = new FavoriteService();
    	$model->tid=$tid;
    	$model->uid=$this->_uid;
    	$model->ctime = time();
    	$ret = $model->save();
    	if($ret){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    	}
    	else{
    		$this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
    	}    	
    }
}
