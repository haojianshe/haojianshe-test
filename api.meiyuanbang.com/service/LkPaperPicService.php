<?php
namespace api\service;

use Yii;
use common\models\myb\LkPaperPic;
use api\service\LkPaperService;
use common\service\CommonFuncService;
/**
 * 
 * @author ihziluoh
 * 
 * 联考
 */
class LkPaperPicService extends LkPaperPic 
{	

	/**
	 * 获取对应分类排行榜
	 * @param  [type]
	 * @param  [type]
	 * @param  [type]
	 * @param  integer
	 * @return [type]
	 */
	public static function getPagerPicRankList($lkid,$rank_type,$lastid=NULL,$rn=50){
	    $redis = Yii::$app->cache;
	    $rediskey="lk_pagerpic_list_".$lkid."_".$rank_type;
	   // $redis->delete($rediskey);
	    $list_arr=$redis->lrange($rediskey,0, -1);
	    //判断缓存是否有内容 若无则重新建立缓存
	    if(empty($list_arr)){
	        $model=self::getPagerPicRankListDb($lkid,$rank_type);
	        $ids='';
	        foreach ($model as $key => $value) {
	            $ids.=$value['picid'].',';
	            $ret = $redis->rpush($rediskey, $value['picid'],true);
	        }
	        $redis->expire($rediskey,60);
	        $ids=substr($ids, 0,strlen($ids)-1);
	        $list_arr=explode(',', $ids);
	    }
	    //分页数据获取
	    if(empty($lastid)){
	        $idx=0;
	        $ids_data=$redis->lrange($rediskey,0, $rn-1);
	    }else{
	        $idx = array_search($lastid, $list_arr);
	        $ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
	    }
	    return $ids_data;
	}
	/**
	 * 数据库获取对应分类排行
	 * @param  [type]
	 * @return [type]
	 */
	public static function getPagerPicRankListDb($lkid,$rank_type){
		$paperids=LkPaperService::getPagerRankListDb($lkid);
		return parent::find()->select("picid")->where(["in",'paperid',$paperids])->andWhere(["status"=>1])->andWhere(['zp_type'=>$rank_type])->orderBy("score desc")->all();
	}

	/**
	 * 获取每张图片详情
	 */
	public static function getPagerPicDetail($picid){
	    $rediskey="lk_pager_pic_".$picid;
	    $redis = Yii::$app->cache;
	    //$redis->delete($rediskey);
	    $detail=$redis->hgetall($rediskey);
	    if (empty($detail)) {
	       $ret=self::findOne(['picid',$picid]);
	       if($ret){
	            $detail=self::findOne(['picid',$picid])->attributes;
	       }
	       $redis->hmset($rediskey,$detail);
	       $redis->expire($rediskey,3600*24*3);
	    }
	    if(json_decode($detail['img_json'])){
	    	$detail['imgs']=json_decode($detail['img_json']);
	    	$detail['imgs']->t=(object)CommonFuncService::getPicByType((array)$detail['imgs']->n,'t');
	    }else{
	    	$detail['imgs']=[];
	    }	    
	    return $detail;
	    
	}
	/**
	 * 通过图片id数组获取详情
	 * 
	 * 
	 * @param  [type]
	 * @return [type]
	 */
	public static function getLkPaperPicsInfo($picids_arr){
		$ret_arr=[];
        foreach ($picids_arr as $key => $value) {
            $info=self::getPagerPicDetail($value);
            if($info){
            	//获取来源及提交用户信息
            	$paperinfo=LkPaperService::getPagerDetail($info['paperid']);
            	$info=array_merge($info,$paperinfo);
                $ret_arr[]=$info;
            }
        }
        return $ret_arr;
	}
	/**
	 * 通过试卷 和分类 获取图片id
	 * 
	 */
	public static function getPagerPicIdByZptype($pagerid,$zp_type){
	    $rediskey="pager_to_pic_".$pagerid."_".$zp_type;
	    $redis = Yii::$app->cache;
	    //$redis->delete($rediskey);
	    $detail=$redis->get($rediskey);
	    if(empty($detail)) {
	       $ret=self::findOne(['paperid'=>$pagerid,"zp_type"=>$zp_type]);
	       if($ret){
	            $detail=$ret->attributes['picid'];
	       }
	       $redis->set($rediskey,$detail);
	       $redis->expire($rediskey,3600*24*3);
	    }
	    return $detail;
	    
	}
	/**
	 * 通过试卷和分类 获取图片信息
	 * @param  [type]
	 * @param  [type]
	 * @return [type]
	 */
	public static function getPagerPicInfoByZptype($pagerid,$zp_type){
		$picid=self::getPagerPicIdByZptype($pagerid,$zp_type);
		return self::getPagerPicDetail($picid);
	}

}