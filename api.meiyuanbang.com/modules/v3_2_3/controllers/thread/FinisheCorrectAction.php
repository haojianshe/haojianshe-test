<?php

namespace api\modules\v3_2_3\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 老师对用户完成的批改
 */
class FinisheCorrectAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        $last_tid = $this->requestParam("last_tid") ? $this->requestParam("last_tid") : 0;
        $uid = $this->requestParam("uid", true); #用户id
        if (!$rn) {
            $rn = 10;
        }
        $ret = array();
        //(1)获取已经批改的帖子列表
        $correctids = CorrectService::getTeacherUserFinisheCorrect($uid, $this->_uid, $last_tid, $rn); # 
        if ($correctids) {
            foreach ($correctids as $k => $value) {
                $ret[] = CorrectService::getListDetailInfo($value['correctid']);
            }
        }
        //老师对用户的全部批改数
        $userCorrectCount = (int) CorrectService::getTeacherSetUserCorrect($uid, $this->_uid);

        if (empty($ret)) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $ret, 'total_count' => $userCorrectCount]);
        }
    }

}
