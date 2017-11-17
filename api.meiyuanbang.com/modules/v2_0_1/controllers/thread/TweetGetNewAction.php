<?php
namespace api\modules\v2_0_1\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CommentService;
use api\service\UserDetailService;

/**
 * 作品首页
 */
class TweetGetNewAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    	
    	//(1)获取最新的帖子列表
    	$tids = TweetService::getPageByUtime(0, $rn,'t');
    	//(2)获取每个帖子的详细信息
    	foreach($tids as $tid){
             $tmp = TweetService::getTweetListDetailInfo($tid,$this->_uid,true);
             if($tmp){
             	$ret[]= $tmp;
             }             
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    }
}
