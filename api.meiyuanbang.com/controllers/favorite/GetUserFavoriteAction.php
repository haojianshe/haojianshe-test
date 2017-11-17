<?php
namespace api\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 收藏列表
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
    	$type = $this->requestParam('type');
    	 
    	//获取列表
    	$tmplist = FavoriteService::getListByUid($this->_uid, $lastfid, $rn);
    	$ret = [];
    	if(!$tmplist || count($tmplist)==0){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    	}
    	//返回帖子列表    	
    	foreach($tmplist as $model) {
    		$tid = $model['tid'];
    		$tweet = TweetService::fillExtInfo($tid, $this->_uid);
    		if(false === $tweet || empty($tweet)) {
    			continue;
    		}
    		$tweet['fid'] = $model['fid'];
    		if($tweet['picnum']>0){
    			$tweet['imgs'] = $tweet['imgs'][0];
    		}    		 
    		$ret[] = $tweet;
    	}
    	$data['content'] = $ret;
    	//兼容老版本添加type参数
    	if($type){
    		$data['type'] = $type;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}