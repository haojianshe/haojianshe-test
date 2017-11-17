<?php

namespace mis\service;

use Yii;
use yii\redis\Cache;
use yii\data\Pagination;
use mis\models\User;
use common\models\myb\UserDetail;
use common\models\myb\StudioTeacher;

/**
 * 用户角色相关逻辑
 */
class UserService extends UserDetail {

    public static function getUserListByQdStat($mobile = NULL, $qd = NULL, $limit = 100) {
        //获取数据      
        $query = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'umobile', 'oauth_type', 'create_time', 'b.qd','r.su','a.professionid','a.provinceid'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->leftJoin('(select uid, min(`ctime`) as ft,max(`lastlogintime`) as lt,  sum(`updatetimes`*3) as su  FROM `myb_user_repeatlogin` GROUP BY `uid`) as r','a.uid=r.uid')
                ->where(['b.register_status' => 0]);
        if ($mobile) {
            //['in','umob1ile',$mobile]
            $query->andWhere("umobile in ($mobile)");
        }
        if ($qd) {
            $query->andWhere(['like', 'b.qd', $qd]);
        }
        $rows = $query->limit($limit)
                ->all();

        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = self::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 根据用户昵称获取用户信息
     * @param $keyword 用户昵称，根据like查询
     * @param $limit 返回的数据条数
     * @return 
     */
    public static function getByName($keyword, $limit = 100) {
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->where("a.sname like '%$keyword%'")
                ->limit($limit)
                ->all();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = self::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return $rows;
    }

    public static function getInfoByUids($uids, $limit = 500) {

        $rows = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'city', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->where("a.uid in ($uids)")
                ->limit($limit)
                ->all();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = self::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return $rows;
    }

    /**
     * 根据用户手机号查找用户
     * $mobile
     */
    public static function getByMobile($mobile, $limit = 500) {
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->where("umobile in ($mobile)")
                ->andWhere(['register_status' => 0])
                ->limit($limit)
                ->all();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = self::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return $rows;
    }

    /**
     * 分页获取认证的老师列表
     */
    public static function getTeacherByPage($sname = '', $select = '', $ukind = '') {
        $query = new \yii\db\Query();
        $query->select(['a.uid', 'sname', 'avatar', 'ukind_verify', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id');
        if ($ukind) {
            $query->where(['ukind' => $ukind]);
        }

        if ($select == 2) {
            if ($sname) {
                $query->andWhere(['like', 'umobile', $sname]);
            }
        } else {
            if ($sname) {
                $query->andWhere(['like', 'sname', $sname]);
            }
        }

        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        //获取数据
        $new_query = new \yii\db\Query();
        $new_query->select(['a.uid', 'sname', 'avatar', 'ukind_verify', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id');
        if ($ukind) {
            $new_query->where(['ukind' => $ukind]);
        }

        #$new_query->where(['ukind' => 1]);
        if ($select == 2) {
            if ($sname) {
                $new_query->andWhere(['like', 'umobile', $sname]);
            }
        } else {

            if ($sname) {
                $new_query->andWhere(['like', 'sname', $sname]);
            }
        }

        $new_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.uid DESC');

        $rows = $new_query->all(); //createCommand()->getRawSql();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = UserService::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 分页获取认证的老师列表
     */
    public static function getTeacherByPageList($sname = '', $select = '', $type = 0) {

        //选中已经选中的列表
        if ($type > 0) {
            $uids = StudioTeacher::find()->select(['uid'])->where(['uuid' => $type])->asArray()->all();
            $uidArr = [];
            if ($uids) {
                foreach ($uids as $k => $v) {
                    $uidArr[$k] = $v['uid'];
                }
            }
            $sname = '';
        }
        $query = new \yii\db\Query();
        $query->select(['a.uid', 'sname', 'avatar', 'ukind_verify', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')->where(['ukind' => 1])
                ->innerJoin('ci_user as b', 'a.uid=b.id');
        if ($type) {
            $query->andWhere(['in', 'a.uid', $uidArr]);
        }
        if ($select == 2) {
            if ($sname) {
                $query->andWhere(['like', 'umobile', $sname]);
            }
        } else {
            if ($sname) {
                $query->andWhere(['like', 'sname', $sname]);
            }
        }

        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        //获取数据
        $new_query = new \yii\db\Query();
        $new_query->select(['a.uid', 'sname', 'avatar', 'ukind_verify', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id');
        $new_query->where(['ukind' => 1]);
        if ($type) {
            $new_query->andWhere(['in', 'a.uid', $uidArr]);
        }
        if ($select == 2) {
            if ($sname) {
                $new_query->andWhere(['like', 'umobile', $sname]);
            }
        } else {

            if ($sname) {
                $new_query->andWhere(['like', 'sname', $sname]);
            }
        }

        $new_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.uid DESC');

        $rows = $new_query->all(); //createCommand()->getRawSql();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = UserService::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 分页获取殿堂老师列表
     */
    public static function getFamousTeacherByPage() {
        $query = parent::find()->where(['ukind_verify' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 25]);
        //获取数据
        $rows = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'ukind_verify', 'umobile', 'oauth_type', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->where(['ukind_verify' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.uid DESC')
                ->all();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = UserService::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 得到用户增加列表 暂时不用
     * @param string $starttime
     * @param string $endtime
     * @return \yii\data\Pagination[]
     */
    public static function getrLoginUserByPage($starttime = '', $endtime = '') {
        $querycount = User::find()->where(['register_status' => 0]);
        if (!empty($starttime)) {
            $querycount->andWhere(['>', 'create_time', $starttime]);
        }
        if (!empty($endtime)) {
            $querycount->andWhere(['<', 'create_time', $endtime]);
        }
        $countQuery = clone $querycount;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 25]);

        $query = new \yii\db\Query();
        $query->select("*")
                ->from(User::tableName())->where(['register_status' => 0]);
        if (!empty($starttime)) {
            $query->andWhere(['>=', 'create_time', $starttime]);
        }
        if (!empty($endtime)) {
            $query->andWhere(['<=', 'create_time', $endtime]);
        }
        $query->join("left join", "ci_user_detail", "ci_user.id=ci_user_detail.uid");
        $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy("id desc");

        $rows = $query->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 得到用户增量 区分类型 按时间查询
     * @param string $start_time
     * @param string $end_time
     * @return mixed
     */
    public static function getAddUserCount($start_time = '', $end_time = '') {
        $where = '';
        if (!empty($start_time)) {
            $where.=" and create_time >= $start_time ";
        }
        if (!empty($end_time)) {
            $where.=" and create_time <= $end_time ";
        }
        $sql = " select 
				 (SELECT count(*) FROM ci_user  where oauth_type='weixin'  and  register_status=0  $where ) as weixin, 
				 (SELECT count(*) FROM ci_user where  oauth_type='qq' and  register_status=0  $where ) as qq, 
				 (SELECT count(*) FROM ci_user where  (oauth_type='weibo' or oauth_type='sina' ) and  register_status=0  $where ) as weibo,
				 (SELECT count(*) FROM ci_user where  `umobile` IS NOT NULL  and  register_status=0  $where ) as mobile,
				 (SELECT count(*) FROM ci_user  where  register_status=0  $where ) as total,
				 (select count(*) from ci_user cu left join (select *  from ci_user_push group by ci_user_push.uid) cp on cp.uid=cu.id where cp.device_type=2  and  register_status=0  $where ) as ios,
				 (select count(*) from ci_user cu left join (select *  from ci_user_push group by ci_user_push.uid) cp on cp.uid=cu.id where cp.device_type=1  and  register_status=0  $where ) as android,
				 (select count(*) from ci_user cu left join (select *  from ci_user_push group by ci_user_push.uid) cp on cp.uid=cu.id where cp.device_type is null  and  register_status=0  $where ) as other;";

        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $counts = $command_count->queryAll()[0];
        $query = "select qd,count(id) as count from ci_user where register_status=0 $where group by qd order by count desc";
        $ci_user_count = $connection->createCommand($query);
        $counts['count'] = $ci_user_count->queryAll();
        return $counts;
    }

    /**
     * 根据用户头像获取src地址
     * @param $avatar 数据库中取出的用户图像地址
     * @param $type 头像大小 n是原始尺寸，s是小图
     */
    public static function getAvatar($avatar, $type = 's') {
        if ($avatar == '') {
            $avatar = 'http://img.meiyuanbang.com/user/default/share_default.png';
        } else {
            $arr = json_decode($avatar, true);
            $avatar = $arr['img'][$type]['url'];
        }
        return $avatar;
    }

    /**
     * 删除在api.meiyuanbang.com里用户相关的缓存
     * $uid
     */
    public static function removecache($uid) {
        $redis = Yii::$app->cache;
        //user_
        $key = "user_" . $uid;
        $redis->delete($key);
        //user_detail_
        $key = "user_detail_" . $uid;
        $redis->delete($key);
        //userext_ 
        $key = "userext_" . $uid;
        $redis->delete($key);
    }

    /**
     * 清除殿堂老师列表的缓存
     * @param unknown $uid
     */
    public static function remove_famousteacher_cache() {
        $redis = Yii::$app->cache;
        $key = "teacher_fame_ids";
        $redis->delete($key);
    }

    /**
     * 获取所有批改老师
     * @param  [type] $where      [description]
     * @param  [type] $where_time [description]
     * @param  [type] $order_by   [description]
     * @return [type]             [description]
     */
    public static function getAllCorrectTeacher($uid = '') {
        if ($uid && is_numeric($uid)) {
            $where = "  uid=$uid and ";
        }
        $connection = Yii::$app->db; //连接      
        //查找
        $sql = "select * from " . parent::tableName() . "  where $where featureflag=1";
        $command = $connection->createCommand($sql);
        $models = $command->queryAll();
        return $models;
    }

    /**
     * 通过用户id获取手机号
     * @param  [type] $uids [description]
     * @return [type]       [description]
     */
    public static function getMobileByUids($uids) {
        $query = new \yii\db\Query();
        //获取数据
        $models = $query
                ->select('umobile')
                ->from(User::tableName())
                ->where(['in', 'id', $uids])
                ->all();
        //返回用户手机号数组
        $mobiles = [];
        foreach ($models as $key => $value) {
            if ($value['umobile']) {
                $mobiles[] = $value['umobile'];
            }
        }
        return $mobiles;
    }

    /*
     * 获取出版社列表
     */

    public static function getPublish() {
        $query = parent::find()->where(['role_type' => 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 25]);
        //获取数据
        $rows = (new \yii\db\Query())
                ->select(['a.uid', 'sname', 'avatar', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->where(['a.role_type' => 2])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.uid DESC')
                ->all();
        //处理头像
        foreach ($rows as $k => $v) {
            $v['avatars'] = UserService::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 
     * @param  $uid int 用户id
     * @return $num int 返回用户图书的数量 
     */
    public static function getBookCount($uid) {
        $query = (new \yii\db\Query())
                ->select(['count(*)'])
                ->from('ci_user_detail as cud')
                ->innerJoin('myb_publishing_book as mpb', 'cud.uid=mpb.uid')
                ->where(['cud.uid' => $uid])
                ->andWhere(['mpb.status' => 1])
                ->andWhere(['cud.role_type' => 2])
                ->count();
        return $query;
    }

    /**
     * 
     * @param type $uid
     */
    public static function getUserCheckboxList($uid) {
        return StudioTeacher::find()->where(['uuid' => $uid])->asArray()->all();
    }

    /**
     * 根据搜索信息获取用户信息
     * $mobile
     */
    public static function getUserInfo($search, $type = 1) {
        $rows = '';
        //获取数据    	
        $rows = (new \yii\db\Query()) # , 'avatar', 'oauth_type'
                ->select(['a.uid', 'sname', 'umobile', 'create_time'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id');
        //按照电话搜索
        if ($type == 1) {
            $rows->where(['umobile' => $search]);
        } else {
            $rows->where(['like', 'sname', $search]);
        }

        return $rows->andWhere(['register_status' => 0])->groupBy(' uid  ')->all(); //->createCommand()->getRawSql();  #
    }

    /**
     * 获取成功邀请时间内的用户数据
     * @param type $strat_time
     * @param type $end_time
     */
    public static function getInviteInfo($strat_time, $end_time, $array = '', $type = 1, $user = '') {
        $query = (new \yii\db\Query())
                ->select(['a.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('ci_user as b', 'a.uid=b.id')
                ->leftJoin('myb_user_repeatlogin as l', 'l.uid=b.id')
                ->innerJoin('ci_user_push as dd', 'dd.uid=b.id')
                ->innerJoin('myb_invite as c', 'b.umobile=c.umobile')
                ->where(['>=', 'b.create_time', strtotime($strat_time)])->groupBy(' l.uid ');
        if ($array && $user) {
            $query = $query->andWhere(['in', 'c.invite_userid', $array]);
        }
        $query->andWhere(['<=', 'b.create_time', strtotime($end_time)])->groupBy(' l.uid ');
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $row = $query->select(['c.invite_id as id', 'c.invite_userid', 'dd.xg_device_token', 'sum(`updatetimes`*3) as hours', 'a.city', 'a.sname as name', 'a.uid', 'c.umobile', 'create_time as invite_time', 'c.ctime as create_time'])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        return ['models' => $row, 'pages' => $pages];
    }

}
