<?php

namespace api\service;

use Yii;
use common\redis\Cache;
use common\models\myb\UserDetail;
use api\service\TeamInfoService;
use api\service\UserRelationService;
use api\service\UserLocationService;
use api\service\TweetService;
use api\service\CorrectService;
use api\service\FavoriteService;
use common\service\DictdataService;
use api\service\RegionService;
use api\service\MiddleSchoolService;
use api\service\UniversityService;

/**
 * 
 * @author Administrator
 *
 */
class UserDetailService extends UserDetail {

    /**
     * 获取用户detail信息，缓存名称和老版本程序相同，老版更新缓存时新的接口会同步更新
     * @param $uid
     * @param $avatar_type 要获取的头像类型 0:s  1:n
     * @return boolean|string
     */
    public static function getByUid($uid, $avatartype = 0, $id2name = true) {
        $redis = Yii::$app->cache;
        $rediskey = "user_detail_" . $uid;

        $ret = $redis->hgetall($rediskey);

        if (!$ret) {

            //从数据库中获取
            $user_temp = static::findOne(['uid' => $uid]);
            if ($user_temp) {

                $ret = $user_temp->attributes;
            }
            if ($ret) {
                //存缓存,保留24*3小时
                $redis->hmset($rediskey, $ret);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }
        }
        //处理头像
        if ($ret && $ret['avatar']) {
            $arr_img_info = json_decode($ret['avatar'], true);
            if ($avatartype == 0) {
                if ($arr_img_info && isset($arr_img_info['img']) && isset($arr_img_info['img']['s']) && isset($arr_img_info['img']['s']['url'])) {
                    //头像http升级到https
                    //$ret['avatar'] = $arr_img_info['img']['s']['url'];
                    $ret['avatar'] = str_replace('http://', 'https://', $arr_img_info['img']['s']['url']);
                } else {
                    $ret['avatar'] = '';
                }
            } else if ($avatartype == 1) {
                if ($arr_img_info && isset($arr_img_info['img']) && isset($arr_img_info['img']['n']) && isset($arr_img_info['img']['n']['url'])) {
                    //头像http升级到https
                    //$ret['avatar'] = $arr_img_info['img']['n']['url'];
                    $ret['avatar'] = str_replace('http://', 'https://', $ret['avatar'] = $arr_img_info['img']['n']['url']);
                } else {
                    $ret['avatar'] = '';
                }
            }
        }
        //把id转换成name
        if ($ret && $id2name) {
            $ret['province'] = DictdataService::getUserProvinceById($ret['provinceid']);
            $ret['gender'] = DictdataService::getGenderByid($ret['genderid']);
            $ret['profession'] = DictdataService::getProfessionById($ret['professionid']);
            //城市
            $ret['city'] = RegionService::getUserCityInfo($ret['city_id'])['region_name'];
            //县
            $ret['area_name'] = RegionService::getUserCityInfo($ret['area_id'])['region_name'];
            //学校 
            $ret['school_name'] = '';
            
            //高中、中学
            if ($ret['professionid'] >= 0 && $ret['professionid'] <= 3) {
                if($ret['school_id']){
                    $ret['school_name'] = MiddleSchoolService::findOne(['schoolid' => $ret['school_id']])->school;
                }
            }
            
            //大学
            if ($ret['professionid'] == 4) {
                if($ret['school_id']){
                    $ret['school_name'] = UniversityService::findOne(['universityid' => $ret['school_id']])->school;
                }
            }
        }
        return $ret;
    }

    /**
     * 老师获取批改列表
     * @param  [type] $uid    [description]
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getTeacherList($lastid, $rn) {
        $redis = Yii::$app->cache;
        $rediskey = 'teacher_fame_ids';
        //$rediskey=$user_correct_list_redis;
        // $redis->delete($rediskey);
        $teacherids_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($teacherids_arr)) {
            // $model =  Correct::findAll(['teacheruid' => $uid]);
            $model = self::getFameTeacher(1000);
            $teacherids = '';
            foreach ($model as $key => $value) {
                $teacherids.=$value . ',';
                $ret = $redis->rpush($rediskey, $value, true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
            $teacherids = substr($teacherids, 0, strlen($teacherids) - 1);
            $teacherids_arr = explode(',', $teacherids);
        }
        //分页数据获取
        if (!isset($lastid)) {
            $idx = 0;
            $correctids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $teacherids_arr);
            $correctids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $correctids_data;
    }

    /**
     * 取殿堂老师id列表，按照最近1个月的点赞和评论数排序
     * 殿堂老师数量有限，暂时采取单个读库模式，如果数量比较多需要修改为一次从数据库获取
     */
    static function getFameTeacher($limit) {
        $ret = array();
        $sort = array();
        //最近30天
        $etime = time();
        $btime = $etime - 3600 * 24 * 30;
        //获取殿堂老师    
        $query = "select uid from ci_user_detail  where ukind_verify=1 limit $limit";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $dbret = $command->queryAll();
        foreach ($dbret as $k => $v) {
            $uid = $v['uid'];
            $ret[] = $uid;
            //获取殿堂老师被点赞数 ,权重为7
            $query = "SELECT count(uid) as 'zancount'  FROM ci_zan 
                    where tid in (select tid from ci_tweet where uid=$uid) 
                    and ctime>=$btime and ctime<=$etime";
            $command = $connection->createCommand($query);
            $row = $command->queryAll()[0];
            //$row = $this->db->query($query)->row_array();
            $sortcount = $row['zancount'] * 7;
            //获取殿堂老师被评论数，权重为3
            $query = "SELECT count(uid) as 'commentcount'  FROM ci_comment 
                    where tid in (select tid from ci_tweet where uid=$uid)
                                and ctime>=$btime and ctime<=$btime";
            $command = $connection->createCommand($query);
            $row = $command->queryAll()[0];
            //$row = $this->db->query($query)->row_array();
            $sortcount += $row['commentcount'] * 3;
            $sort[] = $sortcount;
        }
        //从小到大排序
        array_multisort($sort, $ret);
        return $ret;
    }

    /**
     * 获取殿堂名师的详细信息
     * @param unknown $uids
     * @return multitype:Ambigous <NULL, boolean, number, unknown>
     */
    static function getTeacherInfo($this_uid, $uids) {
        $result = array();
        foreach ($uids as $teacher_uid) {
            //获取userinfo
            $user_data = self::getByUid($teacher_uid);
            //获取用户ext信息
            $user_ext = self::getUserExtInfo($teacher_uid);
            //获取用户小组人数信息,暂存到ext数组里
            $teaminfo = TeamInfoService::getTeaminfoByuid($teacher_uid);
            if (!$teaminfo) {
                $user_ext['membercount'] = 0;
            } else {
                $user_ext['membercount'] = $teaminfo['membercount'];
            }
            //获取follow状态,添加到ext数组
            if ($this_uid == -1) {
                //未登录
                $user_ext['follow_type'] = 0;
            } else {
                $user_ext['follow_type'] = UserRelationService::getBy2Uid($this_uid, $teacher_uid);
            }
            //获取用户位置信息
            $userlocation = UserLocationService::getInfoByUid($teacher_uid);
            //隐藏定位信息        
            $userlocation["lon"] = 0;
            $userlocation["lat"] = 0;
            $result[] = array_merge($user_data, $user_ext, $user_ext, $userlocation);
        }
        return $result;
    }

    /**
     * 获取用户的额外信息，关注数 粉丝数 发帖数
     * @param unknown $uid
     * @return Ambigous <boolean, NULL>|multitype:number unknown
     */
    static function getUserExtInfo($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'userext_' . $uid;
        $redis_ret = $redis->hgetall($redis_key);
        if (!$redis_ret || !isset($redis_ret['follower_num']) || !isset($redis_ret['followee_num']) || !isset($redis_ret['tweet_num']) || !isset($redis_ret['correct_num']) || !isset($redis_ret['fav_num'])) {
            $ext_info = array();
            //$this->load->model('relation_model');
            $ret = UserRelationService::getFollowerNum($uid);
            if ($ret) {
                $ext_info['follower_num'] = $ret;
            } else {
                $ext_info['follower_num'] = 0;
            }
            $ret = UserRelationService::getFolloweeNum($uid);
            if ($ret) {
                $ext_info['followee_num'] = $ret;
            } else {
                $ext_info['followee_num'] = 0;
            }

            $ret = TweetService::getTweetNum($uid);
            if ($ret) {
                $ext_info['tweet_num'] = $ret;
                //隐藏掉帮叔帖子数-temp
                /* if($uid==1){
                  $ext_info['tweet_num'] = 0;
                  } */
            } else {
                $ext_info['tweet_num'] = 0;
            }
            //批改数
            $ret = CorrectService::getUserCorrectCount($uid);
            if ($ret) {
                $ext_info['correct_num'] = $ret;
            } else {
                $ext_info['correct_num'] = 0;
            }
            //批改数
            $ret = FavoriteService::getFavCount($uid);
            if ($ret) {
                $ext_info['fav_num'] = $ret;
            } else {
                $ext_info['fav_num'] = 0;
            }
        } else {
            if ($uid == 1) {
                $redis_ret['tweet_num'] = 0;
            }
            return $redis_ret;
        }

        $redis->hmset($redis_key, $ext_info);
        $redis->expire($redis_key, 172800);
        return $ext_info;
    }

    /**
     * 获取推荐好友的id列表
     * @param unknown $curuid
     * @param unknown $provinceid
     * @param unknown $gender
     * @param unknown $lastid
     * @param unknown $limit
     * @param string $ext 如果相同省份推荐外，获取额外用户的时候，此属性为true
     * @return unknown
     */
    static function getFriendIdsList($curuid, $provinceid, $gender, $lastid, $limit, $ext = false) {
        $query = (new \yii\db\Query())
                ->select(['a.uid'])
                ->distinct(true)
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_tweet as b', 'a.uid=b.uid')  //只推荐发过贴的用户
                ->where(['ukind' => 0]) //过滤加v老师
                ->andWhere(['featureflag' => 0]); //过滤红笔老师	
        //todo 过滤评论团
        //如果用户已经登录则不推荐自己
        if ($curuid != -1) {
            $query = $query->andWhere(['<>', 'a.uid', $curuid]);
        }
        //根据省id取
        if ($provinceid != 0) {
            if ($ext) {
                $query = $query->andWhere(['<>', 'a.provinceid', $provinceid]);
            } else {
                $query = $query->andWhere(['a.provinceid' => $provinceid]);
            }
        }
        //性别条件
        if ($gender != -1) {
            $query = $query->andWhere(['genderid' => $gender]);
        }
        //分页条件
        if ($lastid > 0) {
            $query = $query->andWhere(['<', 'a.uid', $lastid]);
        }
        $ids = $query->limit($limit)
                ->orderBy('uid DESC')
                ->all();
        //返回
        return $ids;
    }

    /**
     * 搜索用户
     * @param unknown $sname
     * @param unknown $lastid
     * @param unknown $limit
     * @return unknown
     */
    static function getIdsList($sname, $lastid, $limit) {
        $query = (new \yii\db\Query())
                ->select(['uid'])
                ->from(parent::tableName())
                ->Where(['like', 'sname', $sname]);
        //分页
        if ($lastid > 0) {
            $query = $query->andWhere(['<', 'uid', $lastid]);
        }
        $ids = $query->orderBy('uid desc')
                ->limit($limit)
                ->all();
        return $ids;
    }

    /**
     * 判断是否红笔老师
     */
    static function isCorrectTeacher($uid) {
        $model = static::getByUid($uid);
        if ($model['featureflag'] == 1) {
            return true;
        }
        return false;
    }

    /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        //清除个人信息缓存
        $redis_key = "user_detail_" . $this->uid;
        $redis->delete($redis_key);
        return $ret;
    }

    //获取用户姓名
    public static function getUserName($uid) {
        return self::find()->select(['sname'])->where(['uid' => $uid])->asArray()->one();
    }

    /**
      更新用戶角色及地理位置
     */
    public static function updateProfessionProvince($uid, $professionid, $provinceid) {
        $useinfo = UserDetailService::findOne(['uid' => $uid]);
        if ($useinfo) {
            $is_save = false;
            if (($professionid || $professionid == 0) && $useinfo->professionid != $professionid) {
                $is_save = true;
                $useinfo->professionid = $professionid;
            }
            if ($provinceid && ($useinfo->provinceid != $provinceid)) {
                $useinfo->provinceid = $provinceid;
                $is_save = true;
            }
            if ($is_save) {
                $useinfo->save();
            }
        }
    }

}
