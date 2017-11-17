<?php
namespace api\controllers\cmt;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\service\ResourceService;
use api\service\UserCoinService;

/**
 * 获取加入小组用户
 */
class NewCmtAction extends ApiBaseAction
{
    public function run()
    {
        $content = array();
        //检查评论类型和主体id必须传入
        $subjecttype=$this->requestParam('subjecttype',true);
        $subjectid=$this->requestParam('subjectid',true);
        $ctype = $this->requestParam('ctype');
        $reply_uid=$this->requestParam('reply_uid');
        $reply_cid=$this->requestParam('reply_cid');
        $ctype = isset($ctype) ? $ctype : 0;
        $reply_uid = isset($reply_uid) ? $reply_uid : 0;
        $reply_cid = isset($reply_cid) ? $reply_cid : 0;
        $uid = $this->_uid;
        if($uid==-1){
            die("用户未登录！！");
        }
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
        }elseif($ctype==3){
             $content = trim($this->requestParam('content',true));
        }
        $model=new CommentService();
        $model->uid = $uid;
        $model->subjecttype = $subjecttype;
        $model->subjectid = $subjectid;
        $model->ctype = $ctype;
        $model->content = $content;
        $model->ctime = time();
        $model->reply_uid = $reply_uid;
        $model->reply_cid = $reply_cid;
        $model->save();
        $cid=$model->attributes['cid'];
        //清除评论数缓存
        CommentService::incCmtCountRedis($subjecttype,$subjectid);

        $addcoincount=0;//UserCoinService::addCoinsByUid($uid,SysMsgTypeEnum::ADDCOIN_COMMENT_TYPE,SysMsgTypeEnum::DAY_COMMENT_MAX_COUNT,SysMsgTypeEnum::NEW_CMMENT_GET_COINS);
        $data['cid']=$cid;
        $data['addcoincount']=$addcoincount;
        if($ctype==1 or $ctype==2){
            $data['resource']=json_decode($model->attributes['content']);
        }else{
            $data['resource']=(object)null;
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
