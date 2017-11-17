<?php
namespace api\modules\v1_2\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CommentService;
use api\service\UserDetailService;

/**
 * 帖子下拉刷新
 * 从老版本移植过来
 * 加入了过滤批改类型帖子的功能
 */
class GetNewAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    	
    	//(1)获取最新的帖子列表
    	$tids = TweetService::getPageByUtime(0, $rn);
    	//(2)获取每个帖子的详细信息
    	foreach($tids as $tid){
             $ret[]=TweetService::getTweetListDetailInfo($tid,$this->_uid,true);
    	}
        //tudo 社区达人 
        $user=UserDetailService::getFameTeacher(3);
        foreach ($user as $key => $value) {
            $detailuser[]= UserDetailService::getByUid($value);
        }
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret,'type'=>'new',"fame_user"=>$detailuser]);
    }
}
