<?php
namespace mis\controllers\vesttweet;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\CommentService;
use mis\service\MisUserVestService;
use common\service\DictdataService;
/**
 * 评论列表
 * 
 */
class CommentAction extends MBaseAction
{
    public $resource_id = 'operation_vesttweet';
    public function run()
    {
        $request = Yii::$app->request;
        //需要当前mis登录用户mis_userid 获取对应马甲列表
        $mis_userid=Yii::$app->user->getIdentity()->mis_userid;
        $tid=$request->get('tid');
        $tweet_uid=$request->get('tweet_uid');
        $request = Yii::$app->request;
        $sname=$request->get('sname');
        $subjecttype=0;
        $where_arr=array();
        //姓名搜索条件
        if(isset($sname) && !empty($sname)){
            $where = "and sname like'%".$sname."%'";
            $where_arr['sname']=$sname;
        }else{
            $where_arr['sname']='';
            $where='';
        }
        if($tid){
            $where.="and subjectid=$tid ";
        }else{
            $where.="";
        }
        //评论对象类型
        if(isset($subjecttype) && is_numeric($subjecttype)){
            $where .= "and subjecttype=$subjecttype";
            $where_arr['subjecttype']=$subjecttype;            
        }else{
            $where.='';
            $where_arr['subjecttype']='';
        }
       
        $data=CommentService::getCommentByPage($where);
        foreach ($data['models'] as $key => $value) {
           $data['models'][$key]['is_vest']=in_array($value['uid'], DictdataService::getVestUser());
        }
        $mis_userid=Yii::$app->user->getIdentity()->mis_userid;
        //获取马甲用户
        $uids=MisUserVestService::getVestUser($mis_userid);
        $uid_array=explode(",", $uids);
        $data['users']=$uid_array;
        //发帖用户
        $data['tweet_uid']=$request->get('uid');
        //帖子id
        $data['tid']=$tid;
        return $this->controller->render('comment', $data);
     
    }
}
