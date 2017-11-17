<?php
namespace api\service;

use Yii;
use common\models\myb\UserRelation;

/**
 * 
 * @author Administrator
 * 用户关注关系
 *
 */
class UserRelationService extends UserRelation 
{
	/**
	 * 获取两个uid的关注关系
	 * @param unknown $follower_uid 关注者
	 * @param unknown $followee_uid 被关注者
	 * 返回值 :0代表user1没关注user2，
	 *       1代表user1关注了user2而user2没关注user1
	 *       2代表互相关注
	 */
	static function getBy2Uid($follower_uid, $followee_uid){
		if($follower_uid == $followee_uid){
			return 0;
		}
		if($follower_uid<$followee_uid){
			$ret = static::findOne(['a_uid'=>$follower_uid,'b_uid'=>$followee_uid]);
			if(!$ret){
				return 0;
			}
			if($ret['a_follow_b']==0){
				//user1没有关注user2
				return 0;
			}
			if($ret['b_follow_a']==0){
				//1关注了2，但是2没有关注1
				return 1;
			}
		}
		else{
			$ret = static::findOne(['a_uid'=>$followee_uid,'b_uid'=>$follower_uid]);
			if(!$ret){
				return 0;
			}
			if($ret['b_follow_a']==0){
				//user1没有关注user2
				return 0;
			}
			if($ret['a_follow_b']==0){
				//1关注了2，但是2没有关注1
				return 1;
			}
		}
		//user1和user2互相关注
		return 2;
	}

	  /**
     * 获取关注$uid的人的数量
     * @param unknown $uid
     * @return unknown
     */
    static function getFollowerNum ($uid) {
 		$connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count  from '.parent::tableName().' where b_uid='.$uid.' and a_follow_b !=0');
        $follower_num = $command->queryAll()[0]['count'];

        $command = $connection->createCommand('select count(*) as count  from '.parent::tableName().' where a_uid='.$uid.' and b_follow_a !=0');
        $follower_num +=  $command->queryAll()[0]['count'];
        return $follower_num;
    }

    /**
     * 获取被$uid关注的人的数量
     * @param unknown $uid
     * @return unknown
     */
   static function getFolloweeNum ($uid) {
		$connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count  from '.parent::tableName().' where a_uid='.$uid.' and a_follow_b !=0');
        $followee_num = $command->queryAll()[0]['count'];

		$command = $connection->createCommand('select count(*) as count  from '.parent::tableName().' where b_uid='.$uid.' and b_follow_a !=0');
        $followee_num +=  $command->queryAll()[0]['count'];
        return $followee_num;
    }

    /**
     * 获取用户关注的人列表 原api迁移 ihziluoh
     * 
     * @param string uid 用户id
     * @param int limit 每页显示条数
     * @param int offset 偏移量
     */
    static function getFolloweeListByUid($uid, $limit, $offset) {
        $query=new \yii\db\Query();
        $arr_rtn = array();
        $arr_result=$query->select('b_uid, b_follow_a')->from(parent::tableName())->where(['a_uid'=> $uid])->andWhere(['<>','a_follow_b',0])->all();
        if (false === $arr_result) {
            return false;
        }
        $user_num=count($arr_result);
        for ($i = 0, $j = $offset; $i < $limit && $j < $user_num; $i++, $j++) {
            $arr_rtn[] = array(
                'uid'   => $arr_result[$j]['b_uid'],
                'follow_type'   => $arr_result[$j]['b_follow_a'] != 0,
            );
        }
        $rtn_size = count($arr_rtn);
        if ($rtn_size >= $limit) {
            return $arr_rtn;
        }
        $offset -= $user_num;
        if ($offset < 0) {
            $offset = 0;
        }
        $limit -= $rtn_size;
        $result_array=$query->select('a_uid, a_follow_b')->from(parent::tableName())->where(['b_uid'=>$uid])->andWhere(['<>','b_follow_a',0])->all();
        if (false === $result_array) {
            return false;
        }
        foreach ($result_array as $item) {
            $arr_rtn[] = array(
                'uid'   => $item['a_uid'],
                'follow_type'   => $item['a_follow_b'] != 0,
            );
        }

        return $arr_rtn;
    }

    /**
     * 获取用户关注的所有人id列表,上限暂定1000 原api迁移 ihziluoh
     * 暂未使用（add by ljq）
     * @param unknown $uid
     */
    static function getAllFolloweeUserIds($uid,$limit=1000) {
        $arrfollow = self::getFolloweeListByUid($uid, $limit, 0);
        if(is_array($arrfollow) && count($arrfollow)>0){
            //只取uid
            foreach($arrfollow as $k=>$v){
                $ret[] = $v['uid'];
            }
            return $ret;
        }
        return null;
    }
}
