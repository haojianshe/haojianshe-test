<?php
namespace api\controllers\systemmessage;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use common\service\CommonFuncService;
use api\service\SystemMessageService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\service\TweetService;
use api\service\CommentService;
/**
 * 获取通知列表
 */
class GetAction extends ApiBaseAction
{
    public function run()
    {
        $trunc_len = 125;
        $uid = $this->_uid;
        $last_id = intval($this->requestParam('last_id'));
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'):10;
        $type = $this->requestParam('type');
        $earliest_id = SystemMessageService::getEarliestMsgId($uid);
        $earliest_id = $earliest_id['sys_message_id'];
        $result = SystemMessageService::getSystemMsg($uid, $last_id, $type,$rn);
        $data = array();
        $msg_list = array();
        $has_more = 1;
        foreach ($result as $msg) {
            $action_type = intval($msg['action_type']);
            if ($action_type == 1) {//过滤私信
                continue;
            }
            $info = array();
            $info['sys_msg_id'] = intval($msg['sys_message_id']);
            $info['timestamp'] = intval($msg['ctime']);
            $info['ctime'] = CommonFuncService::format_time($msg['ctime']);
            if ($info['sys_msg_id'] == $earliest_id)
                $has_more = 0;
            $from_uid = $msg['from_uid'];
            $user_info = UserDetailService::getByUid($from_uid);
            $from_user = array();//TODO user 信息从缓存中获取
            $from_user['sname'] = $user_info['sname'];
            $from_user['avatar'] = $user_info['avatar'];
            $from_user['uid'] = $from_uid;
            $info['from_user'] = $from_user;
            $info['action_type'] = $action_type;
            $info['digest'] = '';
            $info['is_read'] = $msg['is_read'];
            $info['jump'] = '';
            $content_id = $msg['content_id'];
            if ($action_type == SysMsgTypeEnum::AT) {//@
                $info['action_content'] = '@提到了你';
                $info['tid'] = $content_id;
                $community = TweetService::getTweetInfo($content_id);
                $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                $info['digest'] = $digest;
                $info['jump'] = 'meiyuan://tweet?tid='.$content_id;
            }

            if ($action_type == SysMsgTypeEnum::COMMENT) {//回复贴子
                $info['action_content'] = '评论了你的作品';
                $cid = $content_id;//评论id
                $comment = CommentService::getDetailByCid($cid);
                if(!empty($comment)){
                     if($comment){
                        if( $comment['ctype']==1){
                            $comment['content'] =  '[图片]';
                        }
                        if( $comment['ctype']==2){
                            $comment['content'] =  '[语音]';
                        }
                    }

                    $info['tid'] = $comment['subjectid'];
                    $digest = SystemMessageService::trunc($comment['content'], $trunc_len);
                    $info['digest'] = $digest;
                    $info['jump'] = 'meiyuan://tweet?tid='.$info['tid'];
                }
               
            }

            if ($action_type == SysMsgTypeEnum::COMMENT_REPLY) {//回复评论
                $info['action_content'] = '回复了你的评论';
                $cid = $content_id;//评论id
                $comment = CommentService::getDetailByCid($cid);
                if(!empty($comment)){
                    if($comment){
                        if( $comment['ctype']==1){
                            $comment['content'] =  '[图片]';
                        }
                        if( $comment['ctype']==2){
                            $comment['content'] =  '[语音]';
                        }
                    }
                    $info['tid'] = $comment['subjectid'];
                    $digest = SystemMessageService::trunc($comment['content'], $trunc_len);
                    $info['digest'] = $digest;
                    $info['jump'] = 'meiyuan://tweet?tid='.$info['tid'];
                }
               
            }

            if ($action_type == SysMsgTypeEnum::FOLLOW) {
                $info['action_content'] = '关注了你';
                $info['jump'] = 'meiyuan://user?uid='.$from_uid;
            }

            if ($action_type == SysMsgTypeEnum::PRAISE) {
                $community = TweetService::getTweetInfo($content_id);
                //老版本隐藏批改
                if($community['type']==3 || $community['type']==4){
                    continue;
                }
                $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                $info['digest'] = $digest;
                $info['action_content'] = '赞了你的作品';
                $info['tid'] = $msg['content_id'];
               
                $info['jump'] = 'meiyuan://tweet?tid='.$info['tid'];
            }
            
            if ($action_type == SysMsgTypeEnum::TAG) {
                $info['action_content'] = '点评了你的作品';
                $info['tid'] = $msg['content_id'];
                $info['jump'] = 'meiyuan://tweet?tid='.$info['tid'];
            }

            if ($action_type == SysMsgTypeEnum::TWEET_TO_MATERIAL) {//转换成了素材
                $info['action_content'] = '将你的作品加入了素材库';
                $info['tid'] = $content_id;
               /* $community = TweetService::getTweetInfo($content_id);
                $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                $info['digest'] = $digest;*/
                $info['jump'] = 'meiyuan://tweet?tid='.$content_id;
            }

            if ($action_type == SysMsgTypeEnum::TWEET_REC_LESSON) {//推荐超级步骤图
                $info['action_content'] = '为你的作品推荐了步骤图';
                $info['tid'] = $content_id;
               /* $community = TweetService::getTweetInfo($content_id);
                $digest = SystemMessageService::trunc($community['content'], $trunc_len);
                $info['digest'] = $digest;*/
                $info['jump'] = 'meiyuan://tweet?tid='.$content_id;
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
