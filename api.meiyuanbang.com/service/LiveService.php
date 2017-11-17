<?php

namespace api\service;

use common\models\myb\Live;
use Yii;
use common\redis\Cache;
use api\service\UserDetailService;
use api\service\ScanVideoRecordService;
use api\service\VideoResourceService;
use api\service\FavoriteService;
use api\service\LiveSignService;
use api\service\CourseService;
use api\service\OrdergoodsService;
use common\models\myb\StudioTeacher;
use common\models\myb\Orderinfo;
use common\models\myb\AdvResource;
use common\models\myb\ScanVideoRecord;
//use api\service\LiveSignService;
use api\service\UserRelationService;
use api\service\LessonService;
use common\service\DictdataService;

/**
 * 直播相关方法
 */
class LiveService extends Live {

    /**
     * 获取详情
     */
    public static function getDetail($liveid, $uid = -1) {
        $rediskey = "live_detail_" . $liveid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::find()->where(['liveid' => $liveid])->asArray()->one();
            if ($detail) {
                $detail['hits'] = intval($detail['hits']) + intval($detail['hits_basic']);
                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }
        }
        //客服咨询
        $detail['customer_service'] = (object)json_decode($detail['customer_service'],1);
        //直播报名用户数
        $detail['live_sign_count'] = LiveSignService::getLiveSign($liveid);

        //直播状态
        $detail['live_status'] = self::getLiveStatus($detail['start_time'], $detail['end_time']);

        //老师信息
        $detail['userinfo'] = UserDetailService::getByUid($detail['teacheruid']);
        //观看用户列表
        $detail['scanusers'] = ScanVideoRecordService::getScanUser(1, $detail['liveid']);
        //报名状态
        $detail['live_sign_status'] = LiveSignService::getUserSignStatus($liveid, $uid);
        //录播视频信息
        $detail['videoinfo'] = (object) null;
        if (intval($detail['videoid']) > 0) {
            $detail['videoinfo'] = VideoResourceService::getDetail($detail['videoid']);
        }
        //购买状态 付费录播 未支付
        $detail['buy_status'] = OrdergoodsService::getByGoodStatus($uid, 1, $liveid);
        /* if($detail['buy_status']==1 && $detail['recording_price']>0){
          $detail['videoinfo']=(object)null;
          } */

        if ($detail['live_status'] == 1) {
            //预告
            $url = "/video/live/live_trailer?liveid=";
        } else {
            //直播、录播、结束
            $url = "/video/live/live_stream?liveid=";
        }
        // 没type 详情页 type=>1 报名详情页
        $detail['live_url'] = Yii::$app->params['sharehost'] . $url . $liveid;

        $detail['live_sign_url'] = $detail['live_url'] . '&type=1';

        $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['liveid'], 6);

        $tmp2 = 'com';
        $hostaddress = $_SERVER['HTTP_HOST'];

        if ($hostaddress != 'api.meiyuanbang.com' && $hostaddress != 'api1.meiyuanbang.com') {
            $tmp2 = 'cn';
        }
        //生成rtmp播放地址
        $displayKey = '/myb/live' . $tmp2 . '_' . $detail['liveid'] . '-' . $detail['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
        //加密key值
        $k = md5($displayKey);
        //返回rtmp播放地址
        $detail['rtmp_url'] = 'rtmp://live.meiyuanbang.com/myb/live' . $tmp2 . '_' . $detail['liveid'] . '?auth_key=' . $detail['end_time'] . '-0-0-' . $k;
        $detail['adv_info'] = [];
        if ($detail['advid']) {
            $adv_info = AdvResource::find()->where(['advid' => $detail['advid']])->asArray()->one();
            if ($adv_info) {
                $detail['adv_info'][] = $adv_info;
            } else {
                $detail['adv_info'] = [];
            }
        }
        //获取用户老师关注状态
        $detail['follow_type'] = UserRelationService::getBy2Uid($uid, $detail['teacheruid']);
        $detail['live_productid'] =DictdataService::getIosProductidByPrice($detail['live_ios_price']);
        $detail['recording_productid'] =DictdataService::getIosProductidByPrice($detail['recording_ios_price']);

        return $detail;
    }

    /**
     * 判断直播状态
     * @param  [type] $startime [description]
     * @param  [type] $endtime  [description]
     * @return [type]           [description]
     */
    public static function getLiveStatus($startime, $endtime) {
        //1=>正在预告,2=>直播中,3=>直播结束
        $now_time = time();
        $live_status = 1;
        if ($now_time < $startime) {
            $live_status = 1;
        } else if ($now_time > $startime and $now_time < $endtime) {
            $live_status = 2;
        } else if ($now_time > $endtime) {
            $live_status = 3;
        }
        return $live_status;
    }

    /**
     * 获取直播列表
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getLiveList($lastid = NULL, $rn = 50, $f_catalog_id = '') {
        $redis = Yii::$app->cache;
        if ($f_catalog_id) {
            $rediskey = "live_list_" . $f_catalog_id;
        } else {
            $rediskey = "live_list";
        }

        // $redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);

        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getLiveListDb($f_catalog_id);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['liveid'] . ',';
                $ret = $redis->rpush($rediskey, $value['liveid'], true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
            $ids = substr($ids, 0, strlen($ids) - 1);
            $list_arr = explode(',', $ids);
        }
        //分页数据获取
        if (empty($lastid)) {
            $idx = 0;
            $ids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $list_arr);
            $ids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $ids_data;
    }

    /**
     * 数据库获取直播列表
     * @return [type] [description]
     */
    public static function getLiveListDb($f_catalog_id = '') {
        $res = self::find()->select('liveid')->where(['status' => 1]);
        if ($f_catalog_id) {
            $res->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        return $res->orderBy("start_time desc")->all();
    }

    /**
     * 通过直播id 获取直播列表详情数组
     * @param  [type] $liveids [description]
     * @return [type]          [description]
     */
    public static function getListDetail($liveids, $uid = -1) {
        $ret_list = [];
        foreach ($liveids as $key => $value) {
            $ret_list[] = self::getDetail($value, $uid);
        }
        return $ret_list;
    }

    /**
     * 获取老师直播列表
     * @param  [type]  $uid    [description]
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getTeacherLiveList($uid, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "teacher_lives_" . $uid;
        // $redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getTeacherLiveListDb($uid);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['liveid'] . ',';
                $ret = $redis->rpush($rediskey, $value['liveid'], true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
            $ids = substr($ids, 0, strlen($ids) - 1);
            $list_arr = explode(',', $ids);
        }
        //分页数据获取
        if (empty($lastid)) {
            $idx = 0;
            $ids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $list_arr);
            $ids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $ids_data;
    }

    /**
     * 数据库获取老师直播列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherLiveListDb($uid) {
        return self::find()->select("liveid")->where(['teacheruid' => $uid])->andWhere(['status' => 1])->orderBy("liveid desc")->all();
    }

    /**
     * 获取正在直播列表
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getOnlineLiveList($lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "online_live_list";
        // $redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getOnlineLiveListDb();
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['liveid'] . ',';
                $ret = $redis->rpush($rediskey, $value['liveid'], true);
            }
            $redis->expire($rediskey, 300);
            $ids = substr($ids, 0, strlen($ids) - 1);
            $list_arr = explode(',', $ids);
        }
        //分页数据获取
        if (empty($lastid)) {
            $idx = 0;
            $ids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $list_arr);
            $ids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $ids_data;
    }

    /**
     * 数据库获取正在直播
     * @return [type] [description]
     */
    public static function getOnlineLiveListDb() {
        return self::find()->select("liveid")->andWhere(['<', 'start_time', time()])->andWhere(['>', 'end_time', time()])->andWhere(['status' => 1])->orderBy("liveid desc")->all();
    }

    /**
     * 得到用户直播数
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserLiveCount($uid) {
        return self::find()->where(['status' => 1])->andWhere(['teacheruid' => $uid])->count();
    }

    /**
     * 得到画室的直播课列表
     * @param  [type] $uid [description]
     * @return [type] $lastid   [description]
     */
    public static function getStudioLive($uid, $lastid, $rn, $userid, $type = 1) {
        if ($type == 1) {
            $data = self::getStudioTeacherDb($uid, $userid);
            if ($lastid > 0) {
                foreach ($data as $key => $val) {
                    if ($lastid == $val['liveid']) {
                        $array = $key;
                    }
                }
                return (array_slice($data, $array + 1, $rn));
            } else {
                return (array_slice($data, $lastid, $rn));
            }
        } else {
            $data = self::getStudioCourseDb($uid);
            if ($lastid > 0) {
                foreach ($data as $key => $val) {
                    if ($lastid == $val['courseid']) {
                        $array = $key;
                    }
                }
                return (array_slice($data, $array + 1, $rn));
            } else {
                return (array_slice($data, $lastid, $rn));
            }
        }
    }

    /**
     * 根据画室获得所有的老师和直播课
     * @param  [type] $uid [用户id]
     */
    public static function getStudioTeacherDb($uid, $userid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_live_list'; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            $ret = StudioTeacher::find()->select("uid")->where(['uuid' => $uid])->asArray()->all();

            $array = [];
            foreach ($ret as $key => $val) {
                $array[] = $val['uid'];
            }

            $data = (new \yii\db\Query())->select(['b.hits_basic','b.live_price as price', 'b.hits', 'b.liveid', 'b.teacheruid', 'b.live_title', 'b.recording_thumb_url', 'b.videoid', 'b.start_time', 'b.end_time', 'a.sname', 'a.avatar'])
                    ->from('myb_live as b')
                    ->innerJoin('ci_user_detail as a', 'a.uid=b.teacheruid')
                    ->where(['b.teacheruid' => $array])
                    ->andWhere(['b.status' => 1])
                    ->orderBy('b.liveid desc')
                    ->all();
            foreach ($data as $key => $val) {
                if ($userid) {
                    $data[$key]['signcount'] = LiveSignService::find()->where(['liveid' => $val['liveid']])->andWhere(['uid' => $userid])->count(); #->createCommand()->getRawSql()
                } else {
                    $data[$key]['signcount'] = 0;
                }
                $data[$key]['playStatus'] = Orderinfo::find()->where(['mark' => $val['liveid']])->andWhere(['subjecttype' => 3])->andWhere(['status' => 1])->andWhere(['uid' => $userid])->count();
                $data[$key]['avatar'] = json_decode($val['avatar'], 1)['img']['s']['url'];
                if ($val['start_time'] > time()) {
                    $data[$key]['type'] = 1; #预告
                    $data[$key]['singStatus'] = date('m', $val['start_time']) . '月' . date('d', $val['start_time']) . '日 ' . date("H:i", $val['start_time']) . '开播';
                } else if ($val['start_time'] < time() && $val['end_time'] > time()) {
                    $data[$key]['type'] = 2; #直播进行中
                    $data[$key]['singStatus'] = '正在直播';
                } else if ($val['end_time'] < time()) {
                    $data[$key]['type'] = 3; #直播结束
                    $data[$key]['singStatus'] = '已结束';
                }
                $data[$key]['url'] = Yii::$app->params['sharehost'] . '/video/live/live_trailer?liveid=' . $val['liveid'];
            }
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 根据画室获得所有课程
     * @param  [type] $uid [用户id]
     */
    public static function getStudioCourseDb($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_coures_list'; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            $ret = StudioTeacher::find()->select("uid")->where(['uuid' => $uid])->asArray()->all();
            $array = [];
            foreach ($ret as $key => $val) {
                $array[] = $val['uid'];
            }
            $data = (new \yii\db\Query())->select(['b.courseid', 'b.teacheruid', 'b.title', 'b.thumb_url', 'b.supportcount', 'b.hits_basic', 'b.hits'])
                    ->from('myb_course as b')
                    ->where(['b.teacheruid' => $array])
                    ->andWhere(['b.status' => 2])
                    ->orderBy('b.courseid desc')
                    ->all();
            foreach ($data as $key => $val) {
                $data[$key]['price'] = CourseService::getCourseVideoPrice($val['courseid']);
                $data[$key]['url'] = Yii::$app->params['sharehost'] . '/course/detail?courseid=' . $val['courseid'];
            }
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 根据分类获取直播
     * @return [type] [description]
     */
    public static function getLiveidByCatalog($f_catalog_id = 0, $s_catalog_id = 0, $type = "new", $limit = 2) {
        $query = self::find()->select("liveid")->where(['status' => 1]);
        if (intval($f_catalog_id) > 0) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if (intval($s_catalog_id) > 0) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        if ($type == "new") {
            $query->orderBy("liveid desc");
        }
        $lievids = $query->limit($limit)->all();
        $ret_liveids = [];
        foreach ($lievids as $key => $value) {
            $ret_liveids[] = $value['liveid'];
        }
        return $ret_liveids;
    }

    /**
     * 缓存读取分类获取直播啊
     * @param  integer $f_catalog_id [description]
     * @param  integer $s_catalog_id [description]
     * @return [type]                [description]
     */
    public static function getLiveByCorrectidRedis($correctid, $f_catalog_id = 0, $s_catalog_id = 0) {
        $redis = Yii::$app->cache;
        $rediskey = "new_live_correctid_" . $correctid;
        //$redis->delete($rediskey);
        $liveids = $redis->get($rediskey);
        if (empty($liveids)) {
            //一二级分类获取
            $liveids = self::getLiveidByCatalog($f_catalog_id, $s_catalog_id, "new", 2);
            if (count($liveids) < 2) {
                $liveids = self::getLiveidByCatalog($f_catalog_id);
            }
            if ($liveids) {
                $liveids = json_encode($liveids);
                $redis->set($rediskey, $liveids);
                $redis->expire($rediskey, 3600);
            } else {
                $liveids = json_encode($liveids);
            }
        }
        return json_decode($liveids);
    }

    /**
     * 观看记录和历史访问量
     */
    public static function getLiveSignNumber($liveid, $uid, $type = 1) {
        $status = 1;
        //刷新观看记录
        $liveRes = LiveService::findOne(['liveid' => $liveid]);
        //没有播放id
        if (empty($liveRes)) {
            return;
        }
        #$liveRes->hits = $liveRes->hits + 1;
        $redis = Yii::$app->cache;
        //历史记录写入成功后，把观看记录写入
        if ($liveRes->save()) {
            if ($uid > 0) {
                $scanVideoRecord = ScanVideoRecord::findOne(['uid' => $uid, 'subjectid' => $liveid, 'subjecttype' => 1]);
                if (!empty($scanVideoRecord)) {
                    $scanVideoRecord->ctime = time();
                    //修改缓存
                    $newsData = self::getUserScan($liveid, 1);
                    foreach ($newsData as $key => $val) {
                        if ($val['recordid'] == $scanVideoRecord->recordid) {
                            $status = '';
                        }
                    }
                } else {
                    $scanVideoRecord = new ScanVideoRecord();
                    $scanVideoRecord->uid = $uid;
                    $scanVideoRecord->subjecttype = $type;
                    $scanVideoRecord->subjectid = $liveid;
                    $scanVideoRecord->ctime = time();
                }
                $scanVideoRecord->save();
                if ($status) {
                    //清除缓存
                    $rediskey = "scan_live_user_list" . $liveid;
                    $rediskeys = "live_user_list";
                    $redis->delete($rediskeys);
                    $redis->delete($rediskey);
                }
            }
        }
        $redisUserKey = 'scan_video_list' . $uid;
        $redis->delete($redisUserKey);
        $data['data'] = self::getUserScan($liveid, 1);
        #$data['hits'] = $liveRes->hits+$liveRes->hits_basic;
        #$redis->hincrby('live_detail_' . $liveid, 'hits', 1);
        return $data;
    }

    /**
     * 取出前五名观看记录的用户
     * @param int $liveid 直播id
     * @param int $type   标签
     * @return 观看记录的用户
     */
    private static function getUserScan($liveid, $type = 1) {
        $rediskey = "scan_live_user_list" . $liveid;
        $redis = Yii::$app->cache;
        $mlist = $redis->get($rediskey);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())
                    ->select(['b.uid', 'b.avatar', 'a.recordid'])
                    ->from('myb_scan_video_record as  a')
                    ->innerJoin('ci_user_detail as b', 'a.uid=b.uid')
                    ->where(['a.subjecttype' => $type])
                    ->andWhere(['a.subjectid' => $liveid])
                    ->limit(5)
                    ->orderBy('a.ctime DESC')
                    ->all();
            if (!empty($data)) {
                foreach ($data as $val) {
                    $newData[] = [
                        "recordid" => $val['recordid'],
                        "uid" => $val['uid'],
                        'img' => json_decode($val['avatar'], 1)['img']['s']['url']
                    ];
                }
            }
            $mlist = json_encode($newData);
            $redis->set($rediskey, $mlist);
            $redis->expire($rediskey, 60 * 30);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    //获取评论数据
    public static function getCommentDetaid($cid) {
        $redis = Yii::$app->cache;
        $redis_key = 'comment_list_data_' . $cid; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())->select(['a.cid', 'a.uid', 'a.content', 'b.sname'])->from('ci_user_detail as b')
                    ->innerJoin('eci_comment as a', 'a.uid=b.uid')
                    ->where(["a.cid" => $cid])
                    ->andWhere(["a.is_del" => 0])
                    ->one();
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600 * 24);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取评论信息
     */
    public static function getCommentList($liveid) {
        return (new \yii\db\Query())
                        ->select(['a.cid', 'a.uid', 'a.content', 'b.sname'])
                        ->from('ci_user_detail as b')
                        ->innerJoin('eci_comment as a', 'a.uid=b.uid')
                        ->where(['a.subjecttype' => 10])
                        ->andWhere(["a.is_del" => 0])
                        ->andWhere(['a.subjectid' => $liveid])->andWhere(['a.ctype' => 0])
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
    }

    /**
     * 获取评论列表
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getUserCommentList($liveid, $lasttime, $rn = 1000) {
        $redis = Yii::$app->cache;
        $redis_key = 'comment_list_' . $liveid; //缓存key

        static::buildListCache($liveid);
        if ($lasttime == 0) {
            $min = '-inf';
            $ret = $redis->zrangebyscore($redis_key, $min, '+inf', [0, $rn]);
        } else {
            $min = $lasttime * -1;
            $ret = $redis->zrangebyscore($redis_key, $min, '+inf', [1, $rn]);
        }

        if ($ret) {
            return $ret;
        }

        //从数据库获取数据返回		
        $query = self::find()->from('eci_comment')->select("cid")->where(['subjectid' => $liveid])->andWhere(["ctype" => 0])->andWhere(['subjecttype' => 10])->andWhere(['is_del' => 0]);
        if ($lasttime) {
            $query = $query->andWhere(['<', 'ctime', $lasttime]);
        }
        $cids = $query->orderBy('ctime DESC')
                ->limit($rn)
                ->asArray()
                ->all();
        if ($cids) {
            $ret = null;
            foreach ($cids as $cid) {
                $ret[] = $cid['cid'];
            }
            return $ret;
        }
        return null;
    }

    /**
     * 建立评论id列表缓存
     * @param string $tweettype 参考 getPageByUtime中的相同参数
     * @param string $isExsits
     */
    private static function buildListCache($liveid = '', $isExsits = true) {
        $redis = Yii::$app->cache;
        $redis_key = 'comment_list_' . $liveid; //缓存key
        $cachesize = 1000;
        //检查缓存是否存在，如果不存在才重建
        if ($isExsits && $redis->exists($redis_key)) {
            return;
        }
        $redis->delete($redis_key);
        //建立缓存
        $query = self::find()->from('eci_comment')->select("cid,ctime")->where(['subjectid' => $liveid])->andWhere(["ctype" => 0])->andWhere(['subjecttype' => 10])->andWhere(['is_del' => 0]);

        $commentlist = $query->orderBy('ctime DESC')
                ->limit($cachesize)
                ->asArray()
                ->all();
        if ($commentlist) {
            foreach ($commentlist as $model) {
                $utime = $model['ctime'] * -1;
                $redis->zadd($redis_key, $utime, $model['cid']);
            }
            //缓存1天
            $redis->expire($redis_key, 3600 * 24);
        }
    }

    /**
     * 取出数据
     * @param array $caches 1000条评论id数据
     * @param int $rn
     * @param int $last_id
     * @return array 符合条件的评论数据
     */
    public static function CommentList($caches, $rn, $last_id, $liveid) {
        if (empty($caches)) {
            return;
        }
        $array = [];
        if ($last_id == 0) {
            foreach ($caches as $key => $val) {
                if ($key < $rn) {
                    $array[$key] = $val;
                }
            }
        } else {
            $key = array_search($last_id, $caches);
            if ($key) {
                $rns = $key + $rn;
                foreach ($caches as $k => $val) {
                    if ($k <= $rns && $k > $key) {
                        $array[$k] = $val;
                    }
                }
            }
        }
        //如何缓存的数据不够了。就去数据库去取
        if (empty($array)) {
            $query = self::find()->from('eci_comment')->select("cid")->where(['<', "cid", $last_id])->andWhere(['subjectid' => $liveid])->andWhere(['subjecttype' => 10])->andWhere(["ctype" => 0])->andWhere(['is_del' => 0]);
            ;
            $arr = $query->orderBy('ctime DESC')
                    ->limit($rn)
                    #->createCommand()->getRawSql();
                    ->asArray()
                    ->all();
            $array = [];
            if ($arr) {
                foreach ($arr as $kk => $vv) {
                    $array[$kk] = $vv['cid'];
                }
            }
        }
        return $array;
    }

    /**
     * 获取禁言信息表
     */
    public static function getUserBlack($liveid) {

        $newData = [];
        $data = (new \yii\db\Query())
                ->select(['b.uid', 'b.avatar', 'b.sname', 'a.no_talking_time', 'a.ctime'])
                ->from('myb_live_black as  a')
                ->innerJoin('ci_user_detail as b', 'a.uid=b.uid')
                ->where(['a.liveid' => $liveid])
                ->andWhere(['>', 'a.no_talking_time', 0])
                ->andWhere(['>', 'a.ctime', 0])
                ->all();
        $time = time();
        foreach ($data as $val) {
            //提取禁言80秒或永久禁言的用户
            if (($time - $val['ctime']) < 80 || $val['no_talking_time'] > 80) {
                if ($val['cid']) {
                    $newData[] = [
                        "cid" => $val['cid'],
                        "uid" => $val['ctime'],
                        "content" => $val['no_talking_time'],
                        "sname" => $val['sname'],
                    ];
                }
            }
        }
        return $newData;
    }

    /**
     * 增加观看记录浏览量
     * @param [type] $sid [description]
     */
    public static function addHits($liveid) {
        $liveRes = LiveService::findOne(['liveid' => $liveid]);
        #print_R($liveRes);
        //没有播放id
        if (empty($liveRes)) {
            return;
        }
        $liveRes->hits = $liveRes->hits + 1;
        if ($liveRes->save()) {
            $redis = Yii::$app->cache;
            $redis->hincrby('live_detail_' . $liveid, 'hits', 1);
            return $liveRes->hits + $liveRes->hits_basic;
        } else {
            return false;
        }
    }

    /**
     * 获取收藏的直播视频
     * 
     */
    public static function getLiveInfo($liveid) {
        $liveRes = '';
        $liveRes = LiveService::find()->select(['liveid', 'teacheruid','live_price','recording_price', 'live_title', 'recording_thumb_url', 'live_thumb_url', 'start_time'])->where(['liveid' => $liveid])->asArray()->one();
        if ($liveRes) {
            $array = [];
            $url = "/video/live/live_stream?liveid=";
            $liveRes['url'] = Yii::$app->params['sharehost'] . $url . $liveid;
            $liveRes['userinfo'] = UserDetailService::getByUid($liveRes['teacheruid']);
        }
        return $liveRes;
    }

    /**
     * 获取收藏精讲专题
     * @param $subject int 精讲专题id
     */
    public static function getSubjectInfo($subject) {
       
        $liveRes = '';
        $subject = LessonService::getListDetail($subject);
      
        return $subject;
    }
    /**
     * 随机获取首页直播推荐
     * @param  [type] $search_arr [description]
     * @return [type]             [description]
     */
    public static function getHomeLiveRecommendIds($search_arr){
        $ret=[];
        if($search_arr){
            foreach ($search_arr as $key => $value) {
                $liveids=self::find()->select('liveid')->where(['status'=>1])->andWhere(['f_catalog_id'=>$value['f_catalog_id']])->andWhere(['s_catalog_id'=>$value['s_catalog_id']])->limit($value['limit'])->orderBy('rand()')->all();
                foreach ($liveids as $key1 => $value1) {
                    $ret[]=$value1['liveid'];
                }
            }
        }
        return $ret;
    }
}
