<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityService;

/**
 * 获取活动列表
 */
class ListAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	$lastId = $this->requestParam('lastid');
    	if(! $lastId){
    		$lastId = 0;
    	}
    	//(1)获取最新的帖子id列表
    	$ids = ActivityService::getIdsByPage($lastId, $rn);
    	//(2)获取每个帖子的详细信
    	foreach($ids as $k=>$v){
    		$tmp = ActivityService::getDetailById($v);
    		//计算活动进行状态
    		$tmp['executestatus'] = $this->checkExecute($tmp);
    		$tmp['resttime'] = 0;
    		if($tmp['executestatus']==1){
    			//剩余天数
    			$tmp['resttime'] = ceil(($tmp['etime']-time())/(3600*24)); 
    		}
    		$ret[]=$tmp;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    
    /**
     * 0未开始    1正在进行    2已结束
     * @param unknown $model
     */
    private function checkExecute($model){
    	$t = time();
    	if($t<$model['btime']){
    		return 0;
    	}
    	if($t>$model['etime']){
    		return 2;
    	}
    	return 1;
    }
}
