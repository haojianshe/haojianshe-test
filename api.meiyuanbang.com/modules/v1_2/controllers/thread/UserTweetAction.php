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
class UserTweetAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        $last_tid = $this->requestParam("last_tid") ? $this->requestParam("last_tid") : 0;
        $type = $this->requestParam("type") ? $this->requestParam("type") : 0;
        $uid = $this->requestParam("uid", true);//学生id
        if (!$rn) {
            $rn = 10;
        }
        $ret = array();
        //(1)获取最新的帖子列表
        $tids = TweetService::getTidByUid($uid, $last_tid, $rn, $type,$this->_uid);
      
        //(2)获取每个帖子的详细信息
        foreach ($tids as $tid) {
            $ret[] = TweetService::getTweetListDetailInfo($tid['tid'], $this->_uid, true);
        }
        //获取用户已经被批改过的数量
        $userCorrectCount = '';
        if ($type == 4) {
            $userCorrectCount = (int) CorrectService::getTeacherSetUserCorrect($uid,$this->_uid);
        }
        
        if ($type == 1) {
            $userCorrectCount = (int) CorrectService::getUserAllCorrectCount($uid);
        }
        
        if (empty($ret)) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $ret, 'total_count' => $userCorrectCount]);
        }
    }

}
