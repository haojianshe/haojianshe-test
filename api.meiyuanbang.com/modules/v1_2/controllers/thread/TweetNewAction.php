<?php
namespace api\modules\v1_2\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ResourceService;
use common\service\DictdataService;
use api\service\UserCoinService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\CointaskService;
use api\service\UserDetailService;

/**
 * 发新帖
 */
class TweetNewAction extends ApiBaseAction
{
	public function run()
    {
        $uid=$this->_uid;
        $content=$this->requestParam('content',true);
        $imgs=$this->requestParam('imgs',true);
        $tags=$this->requestParam('tags');
        $f_catalog=$this->requestParam('f_catalog');
        $s_catalog=$this->requestParam('s_catalog');
        //处理图片
        $resource_imgs = json_decode($imgs, true);
            if(is_array($resource_imgs)) {
                foreach($resource_imgs as $rimg) {
                    //直接写资源表
                    $resource=new ResourceService();
                    $resource->description= $rimg['content'];
                    unset($rimg['content']);
                    $resource->img=json_encode($rimg);
                    $resource->save();
                    if($resource->attributes['rid']!=false){
                        $rids[] = $resource->attributes['rid'];
                    }                   
                }    
            }
        //tweet直接写库
        $ctime = time();
        $tweet=new TweetService();
        $tweet->uid = $uid;
        $tweet->title = null;
        $tweet->img = null;
        $tweet->content = $content;
        $tweet->tags = isset($tags) ? $tags : "";
        $tweet->type = 2;
        $tweet->resource_id = !empty($rids) ? $data['resource_id'] = implode(',', $rids): '';
        $tweet->ctime = $ctime;
        $tweet->utime = $ctime; //1.2版新增utime，帖子最后更新时;
        $tweet->f_catalog=$f_catalog;
        $tweet->s_catalog=$s_catalog;
        //获取分类id保存
        if($f_catalog){
           $tweet->f_catalog_id = DictdataService::getTweetMainTypeIdByName($f_catalog);
            if($s_catalog){
                $tweet->s_catalog_id = DictdataService::getTweetSubTypeIdByName($tweet->f_catalog_id ,$s_catalog);
            }
        }
        $tweet->save();
        $data['tid']=$tweet->attributes['tid'];
        //加金币
    	if(UserDetailService::isCorrectTeacher($uid)){
    		$tasktype = CointaskTypeEnum::TEACHER_TWEET;
    	}
    	else{
    		$tasktype = CointaskTypeEnum::USER_TWEET;
    	}        
        if(CointaskService::IsAddByDaily($uid, $tasktype)){
        	//需要加金币
        	$coinCount = CointaskDictService::getCoinCount($tasktype);
        	UserCoinService::addCoinNew($uid, $coinCount);
        	$data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
        }
        //兼容老版本加积分
        $data['addcoincount']=0;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
