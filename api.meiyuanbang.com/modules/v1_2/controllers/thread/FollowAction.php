<?php
namespace api\modules\v1_2\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\UserRelationService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 帖子上划刷新
 * 从老版本移植过来
 * 加入了过滤批改类型帖子的功能
 */
class FollowAction extends ApiBaseAction
{
	public function run()
    {       
        $utime=$this->requestParam('utime');
        $lastid=$this->requestParam('lastid') ? $this->requestParam('lastid') : 0;
        $limit=$this->requestParam('rn') ? $this->requestParam('rn') : 10;
        //获取关注用户
        $uids=UserRelationService::getAllFolloweeUserIds($this->_uid);
        //获取关注用户发帖tid
        $tweetList=TweetService::getTidArrByUidArr($uids,$utime,$limit);
        //获取每个帖子的详细信息
    	$ret = [];

    	foreach($tweetList as $model){
            $ret[]=TweetService::getTweetListDetailInfo($model['tid'],$this->_uid);
    	}
        //tudo 社区达人 
        $user=UserDetailService::getFameTeacher(3);
        foreach ($user as $key => $value) {
            $detailuser[]= UserDetailService::getByUid($value);
        }
        $data['content']=$ret;
        $data['fame_user']=$detailuser;
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
