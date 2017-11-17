<?php
namespace api\modules\v2_2_0\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\TweetService;
use api\service\MaterialSubjectService;
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
    	//获取列表
    	$tmplist = FavoriteService::getAllListByUid($this->_uid, $lastfid, $rn);
    	$ret = [];
    	if(!$tmplist || count($tmplist)==0){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    	}
    	//返回收藏列表    	
    	foreach($tmplist as $model) {
            $data_t = $model;
            //区分type 取不同信息
            if($model['type']==0){
                //帖子
                $tid = $model['tid'];
                $tweet = TweetService::fillExtInfo($tid, $this->_uid,true);
                if(false === $tweet || empty($tweet)) {
                    continue;
                }
                //tudo type<3 取平论  批改不带评论
                if($tweet['type']<3){
                    $tweet['comment_list']=TweetService::getCmtRedis(0,$tweet['tid'],2);
                }else{
                    $tweet['comment_list']=array();
                }
                //判断加精 推荐等状态
                $tweet = TweetService::fillFlag($tweet);
                //添加图片列表
                $tweet['imgs_list'] = $tweet['imgs'];
                //多图时显示第一图
                if($tweet['picnum']>0){
                    $tweet['imgs'] = $tweet['imgs'][0];
                }
                //跳转跟着画  0为空 不显示
                if(empty($tweet['lessonid'])){
                    $tweet['lessonid']=0;
                }
                $tweet['fid'] = $model['fid'];
                $data_t['tweet_info'] = $tweet;
                $data_t['material_info'] = (object)array();
                $ret[]=$data_t;
            }else if($model['type']==1){
                //专题
                $tmp = MaterialSubjectService::getMaterialDetail($model['tid']);
                if($tmp){
                   $tmp["picurl"]=json_decode($tmp["picurl"]);
                   $data_t['tweet_info'] = (object)array();
                   $data_t['material_info']= $tmp;
                   $ret[]=$data_t;
                }      
            }    		
    	}
    	$data['content'] = $ret;
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}