<?php
namespace api\modules\v1_3\controllers\comment;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\UserCoinService;
use api\service\TweetService;
use api\service\ResourceService;
use api\service\BlackListService;
use api\service\CointaskService;

/**
 * 评论
 */
class NewCmtAction extends ApiBaseAction
{
    public function run()
    {
        //检查评论类型和主体id必须传入
        $subjecttype=0;
        $subjectid=$this->requestParam('tid',true);
        $reply_uid=$this->requestParam('reply_uid');
        $reply_cid=$this->requestParam('reply_cid');
        $ctype=$this->requestParam('ctype');
        $ctype = isset($ctype) ? $ctype : 0;
        $reply_uid = isset($reply_uid) ? $reply_uid : 0;
        $reply_cid = isset($reply_cid) ? $reply_cid : 0;
        $uid = $this->requestParam('uid',true);

        //黑名单检查（临时）
        $blackmodel = BlackListService::findOne(['uid' => $uid]);
        if($blackmodel){
            //在黑名单内,返回身份错误
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL);
        }

        //评论不同类型处理 图片-1 语音-2 文本-0 
        if($ctype==1){
            //图片
            $file = $_FILES['file'];
            $resource=ResourceService::uploadPicFile('cmt',$file);
            if(!empty($resource['message'])){
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$resource);
            }else{
                $content=json_encode($resource['img']);
            }
        }elseif($ctype==0){
            $content = trim($this->requestParam('content',true));
        }elseif($ctype==2){
            //语音
            $file = $_FILES['file'];
            //时长duration
            $duration=round($this->requestParam('duration',true));
            $talk=ResourceService::uploadTalkFile('cmt',$file,$duration);
            if(!empty($talk['message'])){
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$talk);
            }else{
                $content=json_encode($talk);
            }
        }
        $model=new CommentService();
        $model->uid = $uid;
        $model->subjecttype = $subjecttype;
        $model->subjectid = $subjectid;
        $model->content = $content;
        $model->ctime = time();
        $model->reply_uid = $reply_uid;
        $model->reply_cid = $reply_cid;
        $model->ctype=$ctype;
        $model->save();
        $cid=$model->attributes['cid'];
        //清除评论数缓存
        CommentService::incCmtCountRedis(0,$subjectid);

        $tweet=TweetService::getTweetInfo($subjectid);
        if(empty($tweet)){
            $data['message']='Not Found The Tweet';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }

        //增加了游客功能，如果$uid=-1表示是游客发评论，如果$reply_uid=-1表示回复游客，这两种情况都不发系统消息，SendSysMsgEvent用于向手机发送系统消息
        if($uid !=-1 && $reply_uid !=-1){
            if ($tweet) {
                //判断是否向贴主发送系统消息，$reply_uid=0时必发，如果被回复者是贴主，则不发，只发下面回复的通知
                if(!$reply_uid || ($tweet['uid'] != $reply_uid)) {
                   CommentService::commentPushMsg($uid,$tweet['uid'],$cid);
                }
            }
            //判断是否发回复的系统通知
            if ($reply_uid) {
                    CommentService::commentReplyPushMsg($uid,$reply_uid,$cid);
            }
        }

        //检查是否需要添加评论金币
        $tasktype = CointaskTypeEnum::COMMENT;
        if(CointaskService::IsAddByDaily($uid, $tasktype)){
        	//需要加金币
        	$coinCount = CointaskDictService::getCoinCount($tasktype);
        	UserCoinService::addCoinNew($uid, $coinCount);
        	$data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
        }
        //兼容老版本加金币
        $data['addcoincount']=0;
        $data['cid']=$cid;
        if($ctype==1 or $ctype==2){
            $data['resource']=json_decode($model->attributes['content']);
        }else{
            $data['resource']=(object)null;
        }
        
        //1.2版新增，有最新评论后改变帖子的utime，同时更新缓存使帖子排到最前边        
        $tweet= TweetService::findOne(['tid'=>$subjectid]);
        $tweet->utime = time();
        $tweet->save();

        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
