<?php
namespace mis\controllers\vesttweet;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\CommentService;
use mis\service\UserService;

use mis\service\MisUserVestService;
/**
 * 帖子发评论
 * 
 */
class NewCommentAction extends MBaseAction
{
    public $resource_id = 'operation_vesttweet';

    public function run()
    {
        $request = Yii::$app->request;

        $mis_userid=Yii::$app->user->getIdentity()->mis_userid;
        //获取马甲用户
        $uid=$request->get('uid');
        if($uid){
            $uids=$uid;
        }else{
            $uids=MisUserVestService::getVestUser($mis_userid);
        }
        
        $uid_array=explode(",", $uids);
        $user_infos=array();
        foreach ($uid_array as $key => $value) {
           $user_infos[]=UserService::findOne(["uid"=>$value])->attributes;
        }

         if(!$request->isPost){
             $model=new CommentService();
             return $this->controller->render('newcomment', ['model'=>$model,'isclose'=>false,'msg'=>'',"users"=>$user_infos]);
         }else{
            $tid=$request->get('tid');
           
            $reply_cid=$request->get('reply_cid') ? $request->get('reply_cid') : 0;
            $reply_uid=$request->get('reply_uid') ? $request->get('reply_uid') : 0;
            $model=new CommentService();
            $model->load($request->post());
            if($uid){
               $model->uid=$uid; 
            }
            $model->subjectid=$tid;
            $model->subjecttype=0;
            $model->ctype=0;
            $model->ctime=time();
            $model->is_del=0;
            $model->reply_cid=$reply_cid;
            $model->reply_uid=$reply_uid;
            $model->save();
            return $this->controller->render('newcomment', ['model'=>$model,'isclose'=>true,'msg'=>'保存成功',"users"=>$user_infos]);
         }
    }
}
