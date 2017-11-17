<?php
namespace api\controllers\comment;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取加入小组用户
 */
class PageNewCmtAction extends ApiBaseAction
{   
    public function run()
    {        
        $tid=$this->requestParam('tid',true);
        $uid=$this->requestParam('uid',true);
        $reply_uid=$this->requestParam('reply_uid');
        $reply_cid=$this->requestParam('reply_cid');
        $content = $this->requestParam('content',true);
        if(strlen($content)>180){
            $data['message']='Is Too Long';
            $this->controller->renderJson(ReturnCodeEnum::STARTS_OK, $data);
        }
        $reply_uid = isset($reply_uid) ? $reply_uid : 0;
        $reply_cid = isset($reply_cid) ? $reply_cid : 0;
        $model=new CommentService();
        $model->uid = $uid;
        $model->subjectid = $tid;
        $model->subjecttype=0;
        $model->ctype=0;
        $model->content = $content;
        $model->ctime = time();
        $model->reply_uid = $reply_uid;
        $model->reply_cid = $reply_cid;
        $model->save();
        //清除评论数缓存
        CommentService::incCmtCountRedis(0,$tid);
        $data['cid']=$model->attributes['cid'];
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }
}
