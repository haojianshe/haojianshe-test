<?php
namespace api\modules\v2_0_1\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 作品上划
 */
class TweetGetOldAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    	
    	//lastid没有作用，必传是预留以后可能会用到
    	$lastId = $this->requestParam('last_id',true);
    	$utime = $this->requestParam('utime',true);
    	//(1)获取最新的帖子列表
    	$tids = TweetService::getPageByUtime($utime, $rn,'t');
    	//(2)获取每个帖子的详细信息
    	$ret = [];
    	foreach($tids as $tid){
    		$tmp = TweetService::getTweetListDetailInfo($tid,$this->_uid,true);
    		if($tmp){
    			$ret[]= $tmp;
    		}
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    }
}
