<?php
namespace api\service;

use Yii;
use common\models\myb\Zan;

/**
 * 
 * @author Administrator
 * 帖子赞
 *
 */
class ZanService extends Zan 
{
	/**
	 * 根据帖子id获取赞列表
	 * @param unknown $tid
	 */
	static function getByTid($tid) {
		$redis = Yii::$app->cache;
		$redis_key = 'zanlist_'.$tid;
		//缓存获取
		$ret = $redis->lrange($redis_key, 0, -1);
		if (!$ret || 0 == count($ret)) {
			//从数据库获取
			$zanlist = static::find()->where(['tid'=>$tid])
			->limit(500) //赞列表上限500
			->orderBy('ctime desc')
			->all();
			$ret=[];
			if($zanlist){
				foreach ($zanlist as $zanmodel){
					$ret[] = $zanmodel['uid'];
					//存缓存
					$redis->rpush($redis_key,$zanmodel['uid']);
				}
				//缓存3天
				$redis->expire($redis_key, 3600*24*3);
			}	
		}
		return $ret;
	}
	
	/**
	 * 获取赞过的帖子id列表
	 * @param unknown $uid
	 * @param unknown $lastzanid
	 * @param unknown $limit
	 */
	static function getPageByUid($uid,$lastid,$limit){
		$query = (new \yii\db\Query())
		->select(['zanid','tid'])
		->from(parent::tableName())
		->Where(['uid'=>$uid]);
		//分页
		if($lastid>0){
			$query = $query->andWhere(['<','zanid',$lastid]);
		}
		$ret = $query->orderBy('zanid desc')
		->limit($limit)
		->all();
		return $ret;
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
        $redis_key = 'zanlist_'.$this->tid; 
        $redis->rpush($redis_key,$this->uid); 
        return $ret;
    }
}