<?php
namespace api\modules\v1_3\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 1.3收藏列表
 */
class GetUserFavoriteAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    	
    	$lastfid = $this->requestParam('last_fid');
    	if(!$lastfid){
    		$lastfid=0;
    	}
    	//获取列表
    	$tmplist = FavoriteService::getListByUid($this->_uid, $lastfid, $rn);
    	$ret = [];
    	if(!$tmplist || count($tmplist)==0){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    	}
    	//返回帖子列表    	
    	foreach($tmplist as $favmodel) {
    		$tid = $favmodel['tid'];
    		$model = TweetService::fillExtInfo($tid, $this->_uid);
    		if(false === $model || empty($model)) {
    			continue;
    		}
    		//tudo type<3 取平论  批改不带评论
    		if($model['type']<3){
    			$model['comment_list']=TweetService::getCmtRedis(0,$model['tid'],2);
    		}else{
    			$model['comment_list']=array();
    		}
    		//判断加精 推荐等状态
    		$model = TweetService::fillFlag($model);
    		//添加图片列表
    		$model['imgs_list'] = $model['imgs'];
    		//多图时显示第一图
    		if($model['picnum']>0){
    			$model['imgs'] = $model['imgs'][0];
    		}
    		//跳转跟着画  0为空 不显示
    		if(empty($model['lessonid'])){
    			$model['lessonid']=0;
    		}
    		$model['fid'] = $favmodel['fid'];
    		$ret[] = $model;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content'=>$ret]);	
    }
}