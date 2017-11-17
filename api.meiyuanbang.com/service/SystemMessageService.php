<?php
namespace api\service;

use Yii;
use common\models\myb\SystemMessage;

/**
 * 
 * @author Administrator
 * 私信
 *
 */
class SystemMessageService extends SystemMessage 
{
	/**
	 * 获取未读系统消息的数量
	 * 从缓存中读取数据,如果缓存失效则不用处理
	 * 在系统消息中只存数量,member就是uid,score就是新消息数
	 * @param unknown $uid
	 * @return number|Ambigous <number, unknown>
	 */
	static function getNewMsgCount($uid) {
		$redis = Yii::$app->cache;
		$redis_key = 'ms:msg';
		$ret = 0;
        
		$ret = $redis->zscore($redis_key, $uid);
		if(empty($ret)){
			return 0;
		}
		else{
			return $ret;
		}        
	}
	/**
	 * 根据用户id 获取系统通知列表 包含分页
	 * @param  [type]  $uid     [description]
	 * @param  [type]  $last_id [description]
	 * @param  [type]  $type    [description]
	 * @param  integer $limit   [description]
	 * @return [type]           [description]
	 */
    static function getSystemMsg($uid, $last_id, $type, $limit=10) {
    	 if ($type != 'new'){
    	 	$mark="<";
    	 }else{
    	 	$mark=">";
    	 	$last_id=0;
    	 }
    	$query=new \yii\db\Query();
    	$query = $query->select("*")
    	->from(parent::tableName())
    	->where(['to_uid'=>$uid]);
    	if($last_id>0){
    		$query = $query->andWhere([$mark,'sys_message_id',$last_id]);
    	}
    	return $query->andWhere(['is_del'=>0])
    	->orderBy(['sys_message_id'=>SORT_DESC])    	
    	->limit($limit)
    	->all();
    }
    /**
     * 取最新的一条系统通知
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    static function getEarliestMsgId($uid) {
    	$query=new \yii\db\Query();
    	return $query->select('sys_message_id')->from(parent::tableName())->where(['to_uid'=>$uid])->andWhere(['is_del'=>0])->one();
    }

    /**
	 * 查看列表后后清除小红点
	 * @param unknown $uid
	 * @param unknown $otheruid
	 */
	static function removeRed($uid){
		$rediskey = 'offhubtask';
		$redis = Yii::$app->cachequeue;
		
		$params['tasktype'] = 'clearred';
		$params['tasktctime'] = time();
		$params['uid'] = $uid;
		$params['mType'] = 6;
		$value = json_encode($params);		
		$redis->lpush($rediskey,$value);
	}
    /**
     * 格式化内容
     * @param  [type] $str [description]
     * @param  [type] $len [description]
     * @return [type]      [description]
     */
    static function trunc($str, $len) {
        if (mb_strlen($str, 'utf8') > $len) {
            return mb_substr($str, 0, $len - 3, 'utf8').'...';
        } else {
            return $str;
        }
    }
}
