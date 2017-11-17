<?php

namespace api\modules\v1_3\controllers\systemmessage;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use common\service\CommonFuncService;
use api\service\SystemMessageService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\TweetService;
use api\service\CommentService;
use api\service\CorrectService;
use common\service\dict\CorrectChangeReasonService;
use api\service\CorrectRewardService;

/**
 * 获取通知列表
 */
class GetAction extends ApiBaseAction {

    public function run() {
        $trunc_len = 125;
        $uid = $this->_uid;
        $last_id = intval($this->requestParam('last_id'));
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 10;
        $type = $this->requestParam('type');
        $earliest_id = SystemMessageService::getEarliestMsgId($uid);
        $earliest_id = $earliest_id['sys_message_id'];
        $result = SystemMessageService::getSystemMsg($uid, $last_id, $type, $rn);
        $data = array();
        $msg_list = array();
        $has_more = 1;
        # print_r($result);
        foreach ($result as $msg) {
            $action_type = intval($msg['action_type']);
            if ($action_type == 1) {//过滤私信
                continue;
            }
            $info = [];
            $info['sys_msg_id'] = intval($msg['sys_message_id']);
            $info['timestamp'] = intval($msg['ctime']);
            $info['ctime'] = CommonFuncService::format_time($msg['ctime']);
            if ($info['sys_msg_id'] == $earliest_id)
                $has_more = 0;
            $from_uid = $msg['from_uid'];
            $user_info = UserDetailService::getByUid($from_uid);
            $from_user = array(); //TODO user 信息从缓存中获取
            $from_user['sname'] = $user_info['sname'];
            $from_user['avatar'] = $user_info['avatar'];
            $from_user['uid'] = $from_uid;
            $info['from_user'] = $from_user;
            $info['action_type'] = $action_type;
            $info['digest'] = '';
            $info['is_read'] = $msg['is_read'];
            $info['jump'] = '';
            $content_id = $msg['content_id'];
            switch ($action_type) {
                case SysMsgTypeEnum::AT :
                    $info['action_content'] = '@提到了你';
                    $info['tid'] = $content_id;
                    $community = TweetService::getTweetInfo($content_id);
                    $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                    $info['digest'] = $digest;
                    $info['jump'] = 'meiyuan://tweet?tid=' . $content_id;
                    break;
                case SysMsgTypeEnum::COMMENT : //回复贴子
                    $info['action_content'] = '评论了你的作品';
                    $cid = $content_id; //评论id
                    $comment = CommentService::getDetailByCid($cid);
                    if (!empty($comment)) {
                        if ($comment) {
                            if ($comment['ctype'] == 1) {
                                $comment['content'] = '[图片]';
                            }
                            if ($comment['ctype'] == 2) {
                                $comment['content'] = '[语音]';
                            }
                        }
                        $info['tid'] = $comment['subjectid'];
                        $digest = SystemMessageService::trunc($comment['content'], $trunc_len);
                        $info['digest'] = $digest;
                        //评论了批改以后调到批改
                        $community = TweetService::getTweetInfo($info['tid']);
                        if ($community['type'] == 3 || $community['type'] == 4) {
                            $info['action_content'] = '评论了你的批改';
                            $info['correctid'] = $community['correctid'];
                            $info['type'] = $community['type'];
                            $info['jump'] = 'meiyuan://correct?correctid=' . $info['correctid'];
                        } else {
                            $info['jump'] = 'meiyuan://tweet?tid=' . $info['tid'];
                        }
                    }
                    break;
                case SysMsgTypeEnum::COMMENT_REPLY : //回复评论
                    $info['action_content'] = '回复了你的评论';
                    $cid = $content_id; //评论id
                    $comment = CommentService::getDetailByCid($cid);
                    if (!empty($comment)) {
                        if ($comment) {
                            if ($comment['ctype'] == 1) {
                                $comment['content'] = '[图片]';
                            }
                            if ($comment['ctype'] == 2) {
                                $comment['content'] = '[语音]';
                            }
                        }
                        $info['tid'] = $comment['subjectid'];
                        $digest = SystemMessageService::trunc($comment['content'], $trunc_len);
                        $info['digest'] = $digest;
                        //评论了批改以后调到批改
                        $community = TweetService::getTweetInfo($info['tid']);
                        if ($community['type'] == 3 || $community['type'] == 4) {
                            $info['correctid'] = $community['correctid'];
                            $info['type'] = $community['type'];
                            $info['jump'] = 'meiyuan://correct?correctid=' . $info['correctid'];
                        } else {
                            $info['jump'] = 'meiyuan://tweet?tid=' . $info['tid'];
                        }
                    }
                    break;
                case SysMsgTypeEnum::FOLLOW :
                    $info['action_content'] = '关注了你';
                    $info['jump'] = 'meiyuan://user?uid=' . $from_uid;
                    break;
                case SysMsgTypeEnum::PRAISE :
                    $community = TweetService::getTweetInfo($content_id);
                    //批改跳转到批改详情
                    if ($community['type'] == 3 || $community['type'] == 4) {
                        $correct = CorrectService::getCorrectDetail($community['correctid']);
                        $digest = SystemMessageService::trunc($correct['content'], $trunc_len);
                        $info['digest'] = $digest;
                        $info['action_content'] = '赞了你的批改';
                        $info['correctid'] = $community['correctid'];
                        $info['type'] = $community['type'];
                        $info['tid'] = $msg['content_id'];
                        $info['jump'] = 'meiyuan://correct?correctid=' . $info['correctid'];
                    } else {
                        $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                        $info['digest'] = $digest;
                        $info['action_content'] = '赞了你的作品';
                        $info['tid'] = $msg['content_id'];
                        $info['type'] = $community['type'];
                        $info['jump'] = 'meiyuan://tweet?tid=' . $info['tid'];
                    }
                    break;
                case SysMsgTypeEnum::TAG:
                    $info['action_content'] = '点评了你的作品';
                    $info['tid'] = $msg['content_id'];
                    $info['jump'] = 'meiyuan://tweet?tid=' . $info['tid'];
                    break;
                case SysMsgTypeEnum::TWEET_TO_MATERIAL : //转换成了素材
                    $info['action_content'] = '将你的作品加入了素材库';
                    $info['tid'] = $content_id;
                    $info['jump'] = 'meiyuan://tweet?tid=' . $content_id;
                    break;
                case SysMsgTypeEnum::TWEET_REC_LESSON : //推荐超级步骤图
                    $info['action_content'] = '为你的作品推荐了步骤图';
                    $info['tid'] = $content_id;
                    $info['jump'] = 'meiyuan://tweet?tid=' . $content_id;
                    break;
                case SysMsgTypeEnum::CORRECT_CHANGE : //求批改转作品
                    $info['action_content'] = '把你的求批改转为作品';
                    $correctModel = CorrectService::getCorrectDetail($content_id);
                    $refuseModel = CorrectChangeReasonService::getModelById($correctModel['refuse_reasonid']);
                    if ($refuseModel) {
                        $info['digest'] = "原因:" . $refuseModel['reasondesc'];
                    }
                    $info['tid'] = $correctModel['tid'];
                    $info['jump'] = 'meiyuan://tweet?tid=' . $info['tid'];
                    break;
                case SysMsgTypeEnum::CORRECT_RANK:
                    $tasktype = CointaskTypeEnum::RANK_LIST;
                    $coinCount = CointaskDictService::getCoinCount($tasktype);
                    $community = TweetService::getTweetInfo($content_id);
                    $info['action_content'] = '';
                    $info['digest'] = '您求批改的作品进入了每日排行榜,获得' . $coinCount . '金币';
                    $info['correctid'] = $community['correctid'];
                    $info['type'] = $community['type'];
                    $info['tid'] = $content_id;
                    $info['jump'] = 'meiyuan://correct?correctid=' . $info['correctid'];
                    break;
                case SysMsgTypeEnum::CORRECT_TEACHER_GIFT:
                    $info['gift_pic'] = ''; //价格
                    $info['gift_name'] = '';
                    $info['gift_img_min'] = '';
                    $info['jump'] = 'meiyuan://user?uid=' . $from_uid;
                    $ret = CorrectRewardService::getCorrectRewardGiftInfo($msg['content_id']);
                     $info['action_content'] = '送你了';
                    if ($ret) {
                        $info['gift_pic'] = $ret[0]['gift_price']; //价格
                        $info['gift_name'] = $ret[0]['gift_name'];
                        $info['gift_img_min'] = $ret[0]['gift_img_min'];
                    }
                    break;
            }
            array_push($msg_list, $info);
        }
        $data['msg_list'] = $msg_list;
        $data['has_more'] = $has_more;
        $data['type'] = $type;
        //清楚小红点
        SystemMessageService::removeRed($uid);

        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
