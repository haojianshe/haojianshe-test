<?php

namespace api\modules\v3_1_1\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;
use api\service\TweetService;

/**
 * 获取分页已经批改的作品
 *
 */
class CompleteCorrectAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        //作品rid
        $rid = $this->requestParam('source_pic_rid', true);
        $tids = CorrectService::getCorrectSuccess($rid, 1, 1);
        //(2)获取每个帖子的详细信息
        foreach ($tids as $tid) {
            $tmp = TweetService::getTweetListDetailInfo($tid['tid'], $this->_uid, true);
            if ($tmp) {
                $ret[] = $tmp;
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $ret]);
    }
}
