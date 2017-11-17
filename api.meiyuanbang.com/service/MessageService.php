<?php
namespace api\service;

use Yii;
use common\models\myb\Message;
use common\lib\myb\enumcommon\SysMsgTypeEnum;

/**
 * 
 * @author Administrator
 * 私信
 *
 */
class MessageService extends Message 
{
	/**
	 * 获取未读私信的数量和发信人id集合
	 * 从缓存中读取数据,如果缓存失效则不用处理
	 * z类型的缓存中，member是发消息的uid，score是新消息数
	 * @param unknown $tid
	 * @return number|Ambigous <number, unknown>|multitype:unknown
	 */
	static function getNewMsgInfo($uid) {
		$redis = Yii::$app->cache;
		$redis_key = 'ms:pmsg'.$uid;
		$ret['num'] = 0;
        $ret['from_uid'] = [];
		
		//获取新的私信数
		$from_uids = $redis->zrange($redis_key, 0, -1,true);
		if(empty($from_uids)){
			return $ret;
		}
		foreach ($from_uids as $k=>$v){
			//
			$ret['num'] += $v;
			$ret['from_uid'][] = $k;
		}
		return $ret;
	}
	
	/**
	 * 获取用户交互的最后一条私信的id列表
	 * @param unknown $uid
	 */
	static function getMidsByUid($uid,$mid,$limit){
		//子查询
		$subquery = (new \yii\db\Query())
		->select(['IF(from_uid='.$uid.',to_uid,from_uid) as contactuid', 'max(mid) as maxmid'])
		->from(parent::tableName())
		->where("(from_uid=$uid and from_del=0) or (to_uid=$uid and to_del=0)")
		->groupBy('contactuid')
		->orderBy('maxmid DESC');
		//主查询
		$query = (new \yii\db\Query())
		->select(['maxmid as mid'])
		->from(['a' => $subquery])
		->limit($limit);
		//判断分页
		if($mid != 0){
			$query = $query->where(['<','maxmid',$mid]);			
		}
		//获取数据
		//$query->createCommand()->getRawSql()
		
		$rows = $query->all();
		if($rows){
			foreach ($rows as $k=>$v){
				$ret[]=$v['mid'];
			}
			return $ret;
		}
		return $rows;
	}
	
	/**
	 * 根据mid批量获取
	 * @param unknown $mids
	 * @return unknown
	 */
	static function getByMids($mids){
		$ret = (new \yii\db\Query())
		->select('*')
		->from(parent::tableName())
		->where(['in','mid',$mids])
		->orderBy('mid DESC')
		->all();
		return $ret;
	}
	
	/**
	 * 检查uid是否有未读的发自otheruid的私信
	 * @param unknown $uid
	 * @param unknown $otheruid
	 */
	static function checkNewMsg($uid,$otheruid){
		$redis = Yii::$app->cache;
		
		//私信
		$rediskey = "ms:pmsg" . $uid;
		$ret = $redis->zscore($rediskey,$otheruid);
		if($ret){
			return $ret;
		}
		return 0;
	}
	
	/**
	 * 获取两人对话列表
	 * @param unknown $uid
	 * @param unknown $otheruid
	 * @param unknown $lastmid
	 * @param unknown $limit
	 * @return unknown
	 */
	static function getTalkList($uid,$otheruid,$lastmid,$limit){
		$query = (new \yii\db\Query())
		->select('*')
		->from(parent::tableName())
		->where("(from_uid=$uid and from_del=0 and to_uid=$otheruid) or (to_uid=$uid and to_del=0 and from_uid=$otheruid)");
		//判断分页
		if($lastmid>0){
			$query = $query->andWhere(['<','mid',$lastmid]);
		}
		$ret = $query->orderBy('mid DESC')
		->limit($limit)
		->all();
		return $ret;
	}
	
	/**
	 * 删除两人之间的私信对话
	 * @param unknown $uid
	 * @param unknown $otheruid
	 */
	static function delTalk($uid,$otheruid){
		//(1)更新发给对方的私信的删除标志
		static::updateAll(['from_del'=>1],'from_uid=:from_uid and to_uid=:to_uid',[':from_uid'=>$uid,':to_uid'=>$otheruid]);
		//(2)更新接收对方的私信的删除标志
		static::updateAll(['to_del'=>1],'from_uid=:from_uid and to_uid=:to_uid',[':from_uid'=>$otheruid,':to_uid'=>$uid]);
	}
	
	/**
	 * 查看私信后清除小红点
	 * @param unknown $uid
	 * @param unknown $otheruid
	 */
	static function removeRed($uid,$otheruid){
		$rediskey = 'offhubtask';
		$redis = Yii::$app->cachequeue;
		
		$params['tasktype'] = 'clearred';
		$params['tasktctime'] = time();
		$params['uid'] = $uid;
		$params['mType'] = 8;
		$params['from_uid'] = $otheruid;
		$value = json_encode($params);		
		$redis->lpush($rediskey,$value);
	}
	
	/**
	 * 发站短后发信鸽推送
	 * @param unknown $uid 发信人 
	 * @param unknown $otheruid  收信人
	 * @param unknown $mid 消息id
	 */
	static function pushMessage($uid,$otheruid,$mid){
		$rediskey = 'offhubtask';
		$redis = Yii::$app->cachequeue;
		
		$params['tasktype'] = 'sysmsg';
		$params['tasktctime'] = time();
		$params['from_uid'] = $uid;
		$params['action_type'] = SysMsgTypeEnum::MAIL;
		$params['to_uid'] = $otheruid;
		$params['content_id'] = $mid;
		$value = json_encode($params);
		$redis->lpush($rediskey,$value);
	}
}