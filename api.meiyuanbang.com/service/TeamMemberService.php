<?php
namespace api\service;
use common\models\myb\TeamMember;
use Yii;
use common\redis\Cache;
/**
* 
*/
class TeamMemberService extends TeamMember
{
    public static function getTeamidsByUid($uid,$addtime,$rn){        
       $connection = \Yii::$app->db;
       if($addtime){
        $addtime_sql=" and addtime <".$addtime;
       }else{
        $addtime_sql='';
       }
       $command = $connection->createCommand('select teamid,addtime from '.parent::tableName().' where uid='.$uid.$addtime_sql.' order by addtime desc'.' limit '.$rn);
       $data = $command->queryAll();
       return $data;
    }
    /**
     * 用户搜索
     * @param  [type] $teamid [description]
     * @param  [type] $sname  [description]
     * @return [type]         [description]
     */
    public static function members_search_db($teamid,$sname){
        $query="select cu.*,eci.isadmin from ci_user_detail as cu  inner join ".parent::tableName()." as eci on eci.uid=cu.uid where eci.teamid=$teamid and cu.sname like '%$sname%' limit 50"; // limit $limit
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $data = $command->queryAll();
        return $data;
    }
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
     /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function delete($runValidation = true, $attributeNames = NULL){  
        $redis = Yii::$app->cache;         
        $ret = parent::delete($runValidation,$attributeNames);
        $redis->delete('team_member_'.$this->teamid);
        $redis->delete('team_admins_'.$this->teamid);
        return $ret;
    }
}
