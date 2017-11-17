<?php
namespace api\controllers\thread;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 帖子下拉刷新
 * 从老版本移植过来
 * 加入了过滤批改类型帖子的功能
 */
class GetNewAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    	
    	//(1)获取最新的帖子列表
    	$tweetList = TweetService::getPageByTid(0, $rn,false);
    	//(2)获取每个帖子的详细信息
    	$ret = [];
    	foreach($tweetList as $model){
    		$model = TweetService::fillExtInfo($model['tid'], $this->_uid);
    		if(!$model){
    			continue;
    		}
    		//判断加精 推荐等状态
    		$model = TweetService::fillFlag($model);
    		//添加图片列表
    		$model['imgs_list'] = $model['imgs'];
    		//多图时显示第一图
    		if($model['picnum']>0){
    			$model['imgs'] = $model['imgs'][0]; 
    		} 
    		$ret[] = $model;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret,'type'=>'new']);
    }
}
