<?php
namespace api\controllers\cmt;

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
                $result = CommentService::getListByCidSubjectInc($this->requestParam('first_id'),$subjecttype,$subjectid, $rn);
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
        }
          //处理时间
        foreach ($content as $key => $value) {
           //处理图片语音
           if($content[$key]['ctype']==2){
                $content[$key]['content']='语音';
           }    
           //1专家动态评论 3文章 4考点 5活动 等评论不用带resource对象
           $subtype=$content[$key]['subjecttype'];
           if($subtype==1 || $subtype==3 || $subtype==4 ||$subtype==5){
           		unset($content[$key]['resource']);
           }           
          
          	if(($content[$key]['ctype']==3 || $content[$key]['ctype']==4) && empty($content[$key]['tweet'])){
               	array_splice($content, $key, 1);
               	break;
            }
        }
        $data = array(
            'content' => $content,
            'type' => $subjecttype,    
        );        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
