<?php
namespace api\modules\v1_3\controllers\cmt;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取加入小组用户
 */
class GetAction extends ApiBaseAction
{   
    public function run()
    {              
        $content = array();
        //检查评论类型和主体id必须传入
        $subjecttype=$this->requestParam('subjecttype',true);
        $subjectid=$this->requestParam('subjectid',true);
        $last_cid=$this->requestParam('last_cid');
        $rn=$this->requestParam('rn');
        $version=$this->requestParam('com_version');
        if(empty($rn)){
            if($subjecttype==5){
                //兼容活动评论无法分页问题
                $rn=1000;
            }else{
                $rn=50;
            }
        }
        $first_id=$this->requestParam('first_id');
        if(isset($request['last_cid'])){
          $last_cid = 0;
        }
        if($last_cid == 0){
            if(isset($first_id)){     
                //根据评论id 获取最新的几条评论          
                $result = CommentService::getListByCidSubjectInc($first_id,$subjecttype,$subjectid, $rn);
            }else{
                //获取第一页评论数据
                $result = CommentService::getListBySubject($subjecttype,$subjectid, $rn);
            }
        }
        else{
          //根据last_cid获取第n页的评论
            $result = CommentService::getListByCidSubject($last_cid, $subjecttype,$subjectid,$rn);
        }
        //数据库出错
        if(empty($result)) {
            $data['content']=array();
            $data['type']=$type;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        //评论数据处理
        if($result) {
            $content = $result;
            $content=CommentService::getCmtInfo($result, $subjectid);
            foreach ($content as $key => $value) {
               if($value['ctype']==4 && $version < 311){
                    $value['ctype']=0;
                    $value['content']="该消息为视频课程，请先升级美院帮APP ，再查看。";
                    unset($value['course']);
                    $content[$key]=$value;
               }
            }
        }
        $data = array(
            'content' => $content,
            'type' => $subjecttype,    
        );        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
