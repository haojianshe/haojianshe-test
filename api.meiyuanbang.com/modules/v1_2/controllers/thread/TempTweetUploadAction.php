<?php
namespace api\modules\v1_2\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;
 
use api\service\ResourceService;
use common\service\AliOssService;

use common\models\myb\TempComment;
use common\models\myb\TempTweet;
/**
 * 处理机器人 发帖
 */
class TempTweetUploadAction extends ApiBaseAction
{
    public function run()
    {
        $content=$this->requestParam('content',true);
        $imgurl=$this->requestParam('imgurl',true);
        $f_catalog=$this->requestParam('fcatalog');
        $s_catalog=$this->requestParam('scatalog');
        $tags=$this->requestParam('tags');
        $comment=$this->requestParam('comment');
        $comment_num=$this->requestParam('comment_num');
        $tweettemp= new TempTweet();
        $imgurl=trim($imgurl);
        $img_arr=explode(",", $imgurl);
        $resource_ids='';
        //多图循环获取resource_id
        foreach ($img_arr as $key => $value) {
            $resource= new ResourceService();
           //获取图片信息保存到评论表 得到评论id
            $img_infohw = AliOssService::getFileHW($value);
            if(!$img_infohw){ 
                $data['msg']= '图片错误';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
                exit;
            }else{
                $data['msg']= '';
            }
            $img_info['n']['h']=$img_infohw['height'];
            $img_info['n']['w']=$img_infohw['width'];
            $img_info['n']['url']=$value;
            $resource->img=json_encode($img_info);
            $resource->resource_type=0;
            $resource->save();
            $resource_ids.=$resource->attributes['rid'].',';
        }

        $resource_ids=substr($resource_ids, 0,strlen($resource_ids)-1);
        //得到评论id 保存到临时帖子表
        $tweettemp->resource_id=$resource_ids;
        $tweettemp->content=trim($content);
        $tweettemp->imgurl=$imgurl;
        $tweettemp->f_catalog=trim($f_catalog);
        $tweettemp->s_catalog=trim($s_catalog);
        $tweettemp->total_comment_num=$comment_num;
        $tweettemp->have_comment_num=$comment_num;
        $tweettemp->tags=trim($tags);
        $tweettemp->flag=0;
        $tweettemp->ctime=time().'';
        $tweettemp->save();
        $comment_arr=explode("||||", $comment);
        foreach ($comment_arr as $key => $value) {
            $value=trim($value);
            if(!empty($value)){
                $commenttemp=new TempComment();
                $commenttemp->temptid=$tweettemp->attributes['temptid'];
                $commenttemp->content=$value;
                $commenttemp->flag=0;
                $commenttemp->save();
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
