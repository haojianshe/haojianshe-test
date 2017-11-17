<?php
namespace api\service;

use Yii;
use common\models\myb\CapacitymodelMaterialZan;
use api\service\UserDetailService;
/**
 * 
 * @author Administrator
 *能力模型素材点赞
 *
 */
class CapacityModelMaterialZanService extends CapacitymodelMaterialZan 
{
	/**
	 * 获取能力模型素材点赞用户列表
	 * @param  [type]  $materialid [description]
	 * @param  [type]  $lastid     [description]
	 * @param  integer $rn         [description]
	 * @return [type]              [description]
	 */
	public static function getZanList($materialid,$lastid=NULL,$rn=50){
	    $redis = Yii::$app->cache;
	    $rediskey="capacitymodelmaterialzan_list_".$materialid;
	   // $redis->delete($rediskey);
	    $list_arr=$redis->lrange($rediskey,0, -1);
	    //判断缓存是否有内容 若无则重新建立缓存
	    if(empty($list_arr)){
	        $model=self::getZanListDb($materialid);
	        $ids='';
	        foreach ($model as $key => $value) {
	            $ids.=$value['uid'].',';
	            $ret = $redis->rpush($rediskey, $value['uid'],true);
	        }
	        $redis->expire($rediskey,3600*24*3);
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
	 * 根据能力模型id获取点赞用户列表
	 * @param  [type]  $materialid [description]
	 * @param  [type]  $lastid     [description]
	 * @param  integer $rn         [description]
	 * @return [type]              [description]
	 */
	public static function getZanUserList($materialid,$lastid=NULL,$rn=50){
		$zan_uids=self::getZanList($materialid,$lastid,$rn);
		$zan_users=[];
        foreach ($zan_uids as $key => $value) {
        	$zan_users[]=UserDetailService::getByUid($value);
        }
        return $zan_users;
	}
	/**
	 * 能力模型素材点赞总数
	 * @return [type] [description]
	 */
	public static function getMaterialZanCount($materialid){
		return self::find()->where(['materialid'=>$materialid])->count();
	}
	/**
	 * 数据库获取评论点赞用户列表
	 * @param  [type] $materialid [description]
	 * @return [type]             [description]
	 */
	public static function getZanListDb($materialid){
		$ret=self::find()->select("uid")->where(['materialid'=>$materialid])->orderBy("ctime desc")->asArray()->all();
		if($ret){
			return $ret;
		}else{
			return [];
		}
	}
	/**
	 * 获取用户是否点赞能力模型素材
	 * @param  [type] $materialid [description]
	 * @param  [type] $uid        [description]
	 * @return [type]             [description]
	 */
	public static function getZanStatusByUid($materialid,$uid){
		$is_zan=0;
		$ret=self::find()->select("uid")->where(['materialid'=>$materialid])->andWhere(["uid"=>$uid])->one();
		if($ret){
			$is_zan=1;
		}
		return $is_zan;
	}
 	/**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){       
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation,$attributeNames);  
        $redis_key = 'capacitymodelmaterialzan_list_'.$this->materialid; 
        $redis->delete($redis_key); 
        return $ret;
    }
}