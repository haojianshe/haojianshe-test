<?php
namespace api\modules\v1_3\controllers\zan;

use Yii;
use api\components\ApiBaseAction;
use api\service\ZanService;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取加入小组用户
 */
class PraiseListAction extends ApiBaseAction
{   
    public function run()
    {              
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//lastid没有作用，必传是预留以后可能会用到
    	$lastid = $this->requestParam('lastid');
    	if(!$lastid){
    		$lastid = 0;
    	}
    	//获取lastid和tid列表
    	$ids = ZanService::getPageByUid($this->_uid, $lastid, $rn);
    	$ret = [];
    	foreach ($ids as $k=>$v){
    		$v['tweetinfo'] = TweetService::getTweetInfo($v['tid']);
    		$ret[] = $v;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    }
}