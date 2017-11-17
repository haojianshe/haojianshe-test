<?php
namespace api\service;
use common\models\myb\TeamInfo;
use Yii;
use common\redis\Cache;
/**
* 
*/
class TeamInfoService extends TeamInfo
{
    static  $table_name = 'eci_teammember';
    //小组基本信息cachekey
    static $team_info_key = 'team_info_';
    //根据uid获取到teamid的cachekey
    static $team_uid2teamid_key = 'team_uid2teamid_';
    //根据teamid 获取小组成员数的cachekey
    static $team_member_count="team_member_count_";
    //根据teamid 获取小组管理员cachekey
    static $team_admins="team_admins_";
    //根据teamid 获取小组成员uid cachekey    TEAM_MEMBERS


    public static function getUserTeamInfo($teamid){
         return  TeamInfo::findOne(['teamid'=>$teamid])->attributes;
    }
    /**
     * 通过uid获取小组信息 new
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeaminfoByuid($uid){       
        //从cache获取teamid,根据teamid获取小组信息，如果没有cache则直接从数据库获取信息
        $key = self::$team_uid2teamid_key.$uid;
        $redis = Yii::$app->cache;
        // $redis->delete($key);
        $teamid = $redis->getValue($key);
        if(!$teamid){           
            $result_obj =TeamInfo::findOne(['uid'=>$uid]);
            if ($result_obj) {
                $result=$result_obj->attributes;
            }else{
                $result=array();
            }
            if (0 < $result) {
                //群id存cache,缓存3天
                $redis->setValue($key, $result['teamid'],3600*24*3);
                return $result;
            }
            return null;
        }else{
            return self::getTeaminfoByteamid($teamid);
        }
    }
    /**
     * 通过小组id获取小组信息 new
     * @param  [type] $teamid [description]
     * @return [type]         [description]
     */
    static function getTeaminfoByteamid($teamid){
        //首先从缓存获取
        $redis_key = self::$team_info_key . $teamid;       
        $redis = Yii::$app->cache;
        //$redis->delete($redis_key);
        $redis_ret = $redis->hgetall($redis_key);
        if ($redis_ret) {
            return $redis_ret;
        }
        //缓存中没有取到则从数据库获取
        $result_obj = TeamInfo::findOne(['teamid'=>$teamid]);
        //出错
        if ($result_obj) {
            $result=$result_obj->attributes;
        }else{
            $result=array();
        }
        //未找到记录
        if (0 >= $result) {
            return NULL;
        }
        //找到记录以后先写缓存,记录缓存时间，与userext设置相同时间
        $retredis = $redis->hmset($redis_key, $result);
        $retredis = $redis->expire($redis_key, 3600*24*3);
        return $result;
    }

    /**
     * 获取小组所用用户 new 
     * @param  [type] $teamid  [description]
     * @param  [type] $limit   [description]
     * @param  [type] $last_id [description]
     * @return [type]          [description]
     */
    static function getAllUidByTeamid($teamid){
        $redis_key='team_member_'.$teamid;
        $redis = Yii::$app->cache;
        $teamids=$redis->lrange($redis_key,0,-1);
        //$redis->delete($redis_key);
        if(!$teamids){
            $uids=self::getUidByTeamid($teamid);
            $data=array();
            foreach ($uids as $key => $value) {
                $data[$key]=$value['id'];
                $redis->lpush($redis_key,$value['id']);
            }
            $ret = $redis->expire($redis_key, 3600*24*7);
            if (false === $ret) {
               //log_message('error', 'set cache time error, uid['.$uid.']'); 
            }
            return $data;
        }else{
            return  $teamids;
        }
    }
    /**
     * 通过小组id获取用户id new
     * @param  [type] $teamid [description]
     * @param  [type] $limit  [description]
     * @return [type]         [description]
     */
    static  function getUidByTeamid($teamid) {
        //判断是否获取老数据        
        $query="select uid as id from eci_teammember where teamid=$teamid and isadmin=0  order by addtime asc "; // limit $limit
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $data = $command->queryAll();
        return $data;
    }
    /**
      * 判断是否是小组里的普通用户 new
      * @param  [type]  $uid    [description]
      * @param  [type]  $teamid [description]
      * @return boolean         [description]
      */
    static  function isTeamUser($uid,$teamid){
        return in_array($uid, self::getAllUidByTeamid($teamid));
    }
    /**
     * 通过teamid获取 小组内管理员 new
     * @param  [type] $teamid [description]
     * @return [type]         [description]
     */
    static function getTeamAdminsRedis($teamid){
        $redis_key=self::$team_admins.$teamid; 
        $redis = Yii::$app->cache;
        $admins=$redis->getValue($redis_key);
        //$redis->delete($redis_key);
        if(!$admins){
            $adminuids_arr=self::getAdminsByTeamid($teamid);
            $adminuids_str='';
            foreach ($adminuids_arr as $key => $value) {
              $adminuids_str.=$value['uid'].',';
            }
            $adminuids_str=substr($adminuids_str, 0,strlen($adminuids_str)-1);
            $redis->setValue($redis_key,$adminuids_str,3600*24*7);
            $ret = $redis->expire($redis_key, 3600*24*7);
            return $adminuids_str;
        }
        return $admins;
    }
    /**
     * [getAdminsByTeamid description] new 
     * @param  [type] $teamid [description]
     * @return [type]         [description]
     */
    static function getAdminsByTeamid($teamid){
        $query="select uid from eci_teammember where teamid=$teamid and isadmin=1";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $data = $command->queryAll();
        return $data;
    }
     /**
     * 通过uid判断是否是管理员 new
     * @param  [type]  $uid [description]
     * @return boolean      [description]
     */
    static function isTeamAdmin($uid,$teamid){
        $result=explode("," , self::getTeamAdminsRedis($teamid));       
        return in_array($uid, $result);        
    } 

    /**
     * 评论用户判断是否是管理员 和群主  new 用于评论接口
     * @param  [type] $teamid [description]
     * @param  [type] $uid    [description]
     * @return [type]         [description]
     */
   static function commentUserIsAdmin($teamid,$uid){
        //获取群主uid
        $team_uid=self::getTeaminfoByteamid($teamid);
        if($uid==$team_uid['uid']){
            //是群主
            return '2';
        }
        //获取管理员uid
        $admin_uid_str=self::getTeamAdminsRedis($teamid);
        $admin_uid_arr=explode(",", $admin_uid_str);       
        if(in_array($uid, $admin_uid_arr)){
            //是管理员账户
            return '1';
        }
        //普通用户
        return '0';
    }
    /**
     * 获取小组里的最新加入成员 new 
     * @param  [type] $teamid [description]
     * @return [type]         [description]
     */
    static function getTeamInfoNewuser($teamid){        
        return  array_slice(self::getAllUidByTeamid($teamid), 0, 10); 
    }
    /**
     * 通过小组id 获取小组内成员id列表 （redis 没有则新建） new
     * @param  [type] $teamid  [description]
     * @param  [type] $limit   [description]
     * @param  [type] $last_id [description]
     * @return [type]          [description]
     */
    static function getListByTeamid($teamid,$limit,$last_id){
        $redis = Yii::$app->cache;
        $redis_key='team_member_'.$teamid;
        //$redis->delete($redis_key);
        $res_redis=$redis->lrange($redis_key,0,-1);      
        if(!$res_redis){
            $uids=self::getUidByTeamid($teamid);
            foreach ($uids as $key => $value) {
                $redis->lpush($redis_key,$value['id']);
            }
            $ret = $redis->expire($redis_key, 3600*24*3);
        }
        if($last_id==0){
            //获取最新数据          
            $last_key=0;
            $teaminfoarr=self::getTeaminfoByteamid($teamid); 
            $admin_arr=explode(',', self::getTeamAdminsRedis($teamid));
            //获取最新时添加管理员
            //添加管理员
            if(empty($admin_arr[0])){
                   $data=$redis->lrange($redis_key,$last_key,$last_key+$limit-1);
            }else{
                //有管理员管 理员置顶
                 $all_uid=$redis->lrange($redis_key,$last_key,$last_key+$limit-1);
                 $key = array_search($admin_arr[0],$all_uid);
                 unset($all_uid[$key]);
                 $data=array_merge($admin_arr,$all_uid);
            }
            //添加组创建者
            array_unshift($data, $teaminfoarr['uid']);
            return $data;
        }else{
            //通过动态id 获取获取缓存中的键值
            $last_key=array_keys($redis->lrange($redis_key,0,-1),$last_id)[0]+1;
        }
        if(empty($last_key)){
            //找不到动态id
            return false;
        }else{
            return $redis->lrange($redis_key,$last_key,$last_key+$limit-1);
        }
    }

     /**
     * 从redis里得到小组成员列表  若没有则新建redis new 
     * @param  [type] $teamid  [description]
     * @param  [type] $limit   [description]
     * @param  [type] $last_id [description]
     * @return [type]          [description]
     */
    static function getList($teamid,$limit,$last_id){
        $uids=self::getListByTeamid($teamid,$limit,$last_id);  
        if(!$uids){
            return array();
        }else{
            $team_admins=explode(",", self::getTeamAdminsRedis($teamid));
            foreach ($uids as $key => $value) {
                if(in_array($value,$team_admins)){
                    $data[$key]['isadmin']=1;
                }else{           
                    $data[$key]['isadmin']=0;
                }
                //当获取最新时 添加管理员用户标识
                if($last_id==0){
                    $data[0]['isadmin']=2;
                }
                $data[$key]['uid']=$value;
            }
        return $data;
        }

    }

     /**
     * 保存时操作缓存 new
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){  
        //小组基本信息cachekey
        $team_info_key = 'team_info_';
        //根据teamid 获取小组成员uid cachekey    TEAM_MEMBERS
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;         
        $ret = parent::save($runValidation,$attributeNames);
        if($isnew==false){
            //新建节点需要清理掉对应的缓存
            $redis_key_info = $team_info_key . $this->teamid;
            $redis->delete($redis_key_info);
        }else{
           /* $redis_key_member = $team_info_key.$this->teamid;
            $redis->delete($redis_key_member);*/
        }
        return $ret;
    }
}
