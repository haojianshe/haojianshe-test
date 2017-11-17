<?php
namespace mis\service;

use Yii;
use common\models\myb\TeamMember;
use common\redis\Cache;

/**
 * 小组成员相关方法
 *
 */
class TeamMemberService extends TeamMember 
{
	/**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){  
        //小组基本信息cachekey
        static $team_info_key = 'team_info_';
        //根据uid获取到teamid的cachekey
        static $team_uid2teamid_key = 'team_uid2teamid_';
        //根据teamid 获取小组成员数的cachekey
        static $team_member_count="team_member_count_";
        //根据teamid 获取小组管理员cachekey
        static $team_admins="team_admins_";
        //根据teamid 获取小组成员uid cachekey    TEAM_MEMBERS
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;         
        $ret = parent::save($runValidation,$attributeNames);
        if($isnew==false){
            //新建节点需要清理掉对应的缓存
            $redis_key_admins = $team_admins . $this->teamid;
            $redis->delete($redis_key_admins);

            $redis_key_member = 'team_member_'.$this->teamid;
            $redis->delete($redis_key_member);
        }else{
            $redis_key_member = 'team_member_'.$this->teamid;
            $redis->delete($redis_key_member);
        }
        return $ret;
    }
}
