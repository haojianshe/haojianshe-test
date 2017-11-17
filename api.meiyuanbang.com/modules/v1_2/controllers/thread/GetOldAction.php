<?php
namespace api\modules\v1_2\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 帖子上划刷新
 * 从老版本移植过来
 * 加入了过滤批改类型帖子的功能
 */
class GetOldAction extends ApiBaseAction
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
    	$tids = TweetService::getPageByUtime($utime, $rn);
    	//(2)获取每个帖子的详细信息
    	$ret = [];
    	foreach($tids as $tid){
            $ret[]=TweetService::getTweetListDetailInfo($tid,$this->_uid,true);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret,'type'=>'next']);
    }
}
