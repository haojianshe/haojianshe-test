<?php
namespace api\service;

use Yii;
use common\models\myb\LkPaper;
use api\service\LkPaperPicService;
/**
 * 
 * @author ihziluoh
 * 
 * 联考
 */
class LkPaperService extends LkPaper 
{
	/**
	 * 获取模拟考试卷总分排行榜
	 * @param  [type]
	 * @return [type]
	 */
	public static function getPagerRankList($lkid,$lastid=NULL,$rn=50){
	    $redis = Yii::$app->cache;
	    $rediskey="lk_pager_rank".$lkid;
	    //$redis->delete($rediskey);
	    $list_arr=$redis->lrange($rediskey,0, -1);
	    //判断缓存是否有内容 若无则重新建立缓存
	    if(empty($list_arr)){
	        $model=self::getPagerRankListDb($lkid);
	        $ids='';
	        foreach ($model as $key => $value) {
	            $ids.=$value['paperid'].',';
	            $ret = $redis->rpush($rediskey, $value['paperid'],true);
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
	*
	*  数据库获取联考总分排行榜
	*/
	public static function getPagerRankListDb($lkid){
		return parent::find()->select("paperid")->where(['lkid'=>$lkid])->andWhere(['status'=>1])->orderBy("total_score desc")->asArray()->all();
	}
	/**
	 * 获取多个试卷及图片信息
	 * @param  [type]
	 * @return [type]
	 */
	public static function getLkPagersInfo($pagerid_arr){
		$ret_arr=[];
        foreach ($pagerid_arr as $key => $value) {
        	$info=self::getFullPagerDetail($value);
            if($info){
                $ret_arr[]=$info;
            }
        }
        return $ret_arr;
	}
	/**
	 * 获取试卷 及试卷图片信息
	 * @param  [type]
	 * @return [type]
	 */
	public static function getFullPagerDetail($pagerid){
		$retrun_data=[];
		//'类型 1/2/3 素描/色彩/速写',
		$retrun_data=self::getPagerDetail($pagerid);
		$retrun_data['sumiao']=LkPaperPicService::getPagerPicInfoByZptype($pagerid,1);
		$retrun_data['secai']=LkPaperPicService::getPagerPicInfoByZptype($pagerid,2);
		$retrun_data['suxie']=LkPaperPicService::getPagerPicInfoByZptype($pagerid,3);
		return $retrun_data;
	}
	/**
	 * 获取试卷信息详情
	 */
	public static function getPagerDetail($pagerid){
	    $rediskey="lk_pager_".$pagerid;
	    $redis = Yii::$app->cache;
	    // $redis->delete($rediskey);
	    $detail=$redis->hgetall($rediskey);
	    if (empty($detail)) {
	       $detail=self::find()->select("*")->where(['paperid'=>$pagerid])->asArray()->one();
	       $redis->hmset($rediskey,$detail);
	       $redis->expire($rediskey,3600*24*3);
	    }
		$detail['user']=UserDetailService::getByUid($detail['uid']);
	    return $detail;
	}
	
}