<?php

namespace api\modules\v3_0_2\controllers\live;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;

/**
 * 直播课评论信息列表
 */
class GetLiveListAction extends ApiBaseAction {

    public function run() {
        //直播id
        $liveid = $this->requestParam('liveid', true);
        //评论最后的id
        $last_id = $this->requestParam('last_id');
        if (empty($last_id)) {
            $type = 1;
            $lasttime = 0;
        } else {
            $type = 0;
            $lasttime = time() + 4;
        }
        //条数
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 20;
        }
        //评论类型
        $subjecttype = 10;
        //第一次获取数据 [该直播课的所有评论]
        $cache = LiveService::getUserCommentList($liveid, $lasttime);
      
        if ($cache) {
            rsort($cache);
        }
        $commentList = LiveService::CommentList($cache, $rn, $last_id, $liveid);
       
        //(2)获取每个评论的详细信息
        $data = [];
        if ($commentList) {
            foreach ($commentList as $cid) {
                $tmp = LiveService::getCommentDetaid($cid);
                if ($tmp) {
                    $data[] = $tmp;
                }
            }
        }
        //获取黑名单用户 追加到评论列表里面
        //        if ($type) {
        //            $resut = LiveService::getUserBlack($liveid);
        //        } else {
        //            $resut = [];
        //        }
        $resut = [];
        $ret = array_merge($data, $resut);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
