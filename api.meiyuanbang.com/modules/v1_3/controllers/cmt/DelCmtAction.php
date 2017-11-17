<?php
namespace api\modules\v1_3\controllers\cmt;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取加入小组用户
 */
class DelCmtAction extends ApiBaseAction
{   
    public function run()
    {
        $content = array();
        //检查评论类型和主体id必须传入
        $cid=$this->requestParam('cid',true);
        $uid = $this->_uid;

        $comment=CommentService::findOne(['cid'=>$cid]);

        if(!$comment){
            $data['message']='Not Found This Comment';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }

        if($comment['uid']!= $uid){
            $data['message']='Permission Denied';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $comment->is_del=1;
        $comment->save();
        //更改缓存
        CommentService::decCmtCountRedis($comment['subjecttype'],$comment['subjectid']);
        $data['cid']=$cid;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
