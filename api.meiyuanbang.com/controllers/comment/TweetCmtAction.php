<?php
namespace api\controllers\comment;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\CommonFuncService;

/**
 * 获取加入小组用户
 */
class TweetCmtAction extends ApiBaseAction
{
    public function run()
    {
        //如果没传rn参数则取默认值，后端统一控制展现数量
        $rn=$this->requestParam('rn');
        if(!isset($rn)){
            $rn=50;
        }
        $tid=$this->requestParam('tid',true);
        $type=$this->requestParam('type',true);
        $cid=$this->requestParam('last_cid');
        $content = array();
        if($type == 'new') {
            //下拉刷新取最新评论
            $result = CommentService::getListBySubject(0,$tid, $rn);
        }elseif($type == 'next') {
            //上拉加载更多评论
            $cid = $cid ? $cid : 0;
            $result = CommentService::getListByCidSubject($cid,0, $tid, $rn);
        }
        if(empty($result)) {
            $data['content']=array();
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $content=CommentService::getCmtInfo($result,$tid);
        //处理时间
        foreach ($content as $key => $value) {
           $content[$key]['ctime']=CommonFuncService::format_time($value['ctime']);
           //处理图片语音
           if($content[$key]['ctype']==1){
                $content[$key]['content']='图片';
           }elseif($content[$key]['ctype']==2){
                $content[$key]['content']='语音 请升级app后收听';
           }    
        }
        $data = array(
            'content' => $content,
            'type' => $type,
        );
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
