<?php

namespace api\service;

use api\service\CorrectTalkService;
use api\service\ResourceService;
use api\service\UserDetailService;
use api\service\UserRelationService;
use common\service\CommonFuncService;
use common\service\dict\CorrectRefuseReasonService;
use api\service\UserCorrectService;
use common\models\myb\Correct;
use api\service\FavoriteService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use Yii;
use common\redis\Cache;

/**
 * 
 */
class CorrectService extends Correct {   //学生批改列表

    static $user_correct_list_redis = 'user_correct_list_';
    //老师批改列表
    static $teacher_correct_list_redis = 'teacher_correct_list_';
    //单个批改详情    
    static $correct_detail_redis = 'correct_detail_';
    //批改过的老师列表
    static $has_correct_redis = 'has_correct_list_';

    /**
     * 学生获取批改列表
     * @param  [type] $uid    [description]
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getUserCorrectList($uid, $lastid, $rn) {
        $redis = Yii::$app->cache;
        $user_correct_list_redis = self::$user_correct_list_redis;
        $rediskey = $user_correct_list_redis . $uid;
        //$redis->delete($rediskey);
        $correctids_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($correctids_arr)) {
            $model = self:: getUserCorrectids($uid);
            $correctids = '';
            foreach ($model as $key => $value) {
                $correctids.=$value['correctid'] . ',';
                $ret = $redis->rpush($rediskey, $value['correctid'], true);
            }
            $correctids = substr($correctids, 0, strlen($correctids) - 1);
            $correctids_arr = explode(',', $correctids);
        }
        //分页数据获取
        if (!isset($lastid)) {
            $idx = 0;
            $correctids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $correctids_arr);
            $correctids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $correctids_data;
    }

    /**
     * 老师获取批改列表
     * @param  [type] $uid    [description]
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getTeacherCorrectList($uid, $lastid, $rn) {
        $redis = Yii::$app->cache;
        $user_correct_list_redis = self::$teacher_correct_list_redis;
        $rediskey = $user_correct_list_redis . $uid;
        // $redis->delete($rediskey);
        $correctids_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($correctids_arr)) {
            $model = self:: getTeacherCorrectids($uid);
            $correctids = '';
            foreach ($model as $key => $value) {
                $correctids.=$value['correctid'] . ',';
                $ret = $redis->rpush($rediskey, $value['correctid'], true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
            $correctids = substr($correctids, 0, strlen($correctids) - 1);
            $correctids_arr = explode(',', $correctids);
        }
        //分页数据获取
        if (!isset($lastid)) {
            $idx = 0;
            $correctids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $correctids_arr);
            $correctids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $correctids_data;
    }

    /**
     * 数据库获取学习批改id列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherCorrectids($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select correctid from myb_correct where teacheruid=' . $uid . ' order by correctid desc');
        $data = $command->queryAll();
        return $data;
    }

    /**
     * 通过批改状态获取老师批改列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherCorrectidsByStatus($uid, $status, $lastid = false, $limit = 0) {

        if ($lastid) {
            $string = "and correctid < " . $lastid;
        } else {
            $string = '';
        }
        if ($limit == 0) {
            $limit = "";
        } else {
            $limit = "limit " . $limit;
        }

        $connection = \Yii::$app->db;

        if($status==0){
            $orderby="correct_fee desc,correctid desc";
        }else{
            $orderby="correctid desc";
        }

        $sql='select correctid from myb_correct where teacheruid=' . $uid . ' and (correct_fee=0 or (correct_fee>0 and pay_status=1)) and status=' . $status . ' ' . $string . ' order by '.$orderby.' '. $limit;

        $command = $connection->createCommand($sql);

        $data = $command->queryAll();

        return $data;
    }

    /**
     * 通过批改状态获取学生批改列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCorrectidsByStatus($uid, $status, $lastid = false, $limit = 0) {
        $query = (new \yii\db\Query())
                ->select('correctid')
                ->from(parent::tableName())
                ->where(['submituid' => $uid])
                ->andWhere("(correct_fee=0 or (correct_fee>0 and pay_status=1))");
        //分页，适用于已批改作品
        if ($lastid) {
            $query = $query->andWhere(['<', 'correctid', $lastid]);
        }
        if ($status == 0) {
            //未批改包括未批和拒批两种
            $query = $query->andWhere("(status=0 or status=3)");
        } else {
            //已批改
            $query = $query->andWhere(['status' => $status]);
        }
        //已批改分页功能
        if ($limit) {
            $query = $query->limit($limit);
        }
        $ret = $query->orderBy('correctid DESC')->all();
        return $ret;
    }

    /**
     * 获取老师已批改列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherCorrectCount($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count from myb_correct where status=1 and (correct_fee=0 or (correct_fee>0 and pay_status=1)) and teacheruid=' . $uid);
        $data = $command->queryAll();
        return $data[0]['count'];
    }

    /**
     * 获取学生已批改数量
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherSetUserCorrect($uid, $teacherid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count from myb_correct where status=1 and (correct_fee=0 or (correct_fee>0 and pay_status=1)) and submituid=' . $uid . ' and teacheruid=' . $teacherid);
        $data = $command->queryAll();
        return $data[0]['count'];
    }

    /**
     * 获取学生已批改数量
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCorrectCount($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count from myb_correct where status=1 and (correct_fee=0 or (correct_fee>0 and pay_status=1)) and submituid=' . $uid);
        $data = $command->queryAll();
        return $data[0]['count'];
    }

    /**
     * 获取学生批改总数量
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserAllCorrectCount($uid, $f_catalog_id = '') {
        $connection = \Yii::$app->db;
        $andwhere = '';
        if ($f_catalog_id) {
            $andwhere = " and f_catalog_id=$f_catalog_id";
        }
        $command = $connection->createCommand('select count(*) as count from myb_correct where (status=1 or status=0 or status =3) and (correct_fee=0 or (correct_fee>0 and pay_status=1))  and submituid=' . $uid . $andwhere);
        $data = $command->queryAll();
        return $data[0]['count'];
    }

    /**
     * 数据库获取老师批改id列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCorrectids($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select correctid from myb_correct where submituid=' . $uid . ' and (correct_fee=0 or (correct_fee>0 and pay_status=1)) order by correctid desc');
        $data = $command->queryAll();
        return $data;
    }

    /**
     * 获取单个批改信息
     * @param  [type] $correctid [description]
     * @return [type]            [description]
     */
    //todo  hset 
    public static function getCorrectDetail($correctid) {
        $correct_detail_redis = self::$correct_detail_redis;
        $rediskey = $correct_detail_redis . $correctid;
        $redis = Yii::$app->cache;

        $correct_detail = $redis->hgetall($rediskey);
        if (empty($correct_detail)) {
            $data = Correct::findOne(['correctid' => $correctid])->attributes;
            //如果是拒批，添加拒批理由
            if ($data['status'] == 3 && $data['refuse_reasonid']) {
                $reasonModel = CorrectRefuseReasonService::getModelById($data['refuse_reasonid']);
                if ($reasonModel) {
                    $data['refuse_reason'] = $reasonModel['reasondesc'];
                }
            }
            $redis->hmset($rediskey, $data);
            $redis->expire($rediskey, 3600 * 24);
            return $data;
        } else {
            return $correct_detail;
        }
    }

    /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $correct_detail_redis = self::$correct_detail_redis;
        $user_correct_list_redis = self::$user_correct_list_redis;
        $teacher_correct_list_redis = self::$teacher_correct_list_redis;
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        $rediskey = $correct_detail_redis . $this->correctid;
        //处理缓存
        $data = Correct::findOne(['correctid' => $this->correctid])->attributes;
        if ($isnew == false) {
            //编辑清除缓存
            $redis->delete($rediskey);
            if (!in_array($data['teacheruid'], $redis->lrange(self::$has_correct_redis . $data['submituid'], 0, -1))) {
                $redis->rpush(self::$has_correct_redis . $data['submituid'], $data['teacheruid'], true);
            }
            $redis_key_user = 'userext_' . $data['submituid'];
            $redis->delete($redis_key_user);
        } else {
            //增加批改详情缓存
            $redis->hmset($rediskey, $data);
            //更改学生批改列表缓存
            $user_rediskey = $user_correct_list_redis . $data['submituid'];
            $redis->lpush($user_rediskey, $this->correctid);
            $redis->expire($user_rediskey, 3600 * 24 * 3);
            //更改老师批改列表缓存
            $teacher_rediskey = $teacher_correct_list_redis . $data['teacheruid'];
            $redis->lpush($teacher_rediskey, $this->correctid);
            $redis->expire($teacher_rediskey, 3600 * 24 * 3);
            //分类列表
            //临时修改by ljq,此处缓存会引起缓存内数据不全
            //$redis->lpush($catalog_list_rediskey,$this->correctid);
            //$redis->expire($teacher_rediskey, 3600*24*3);
            $redis->delete($catalog_list_rediskey);
        }
        return $ret;
    }

    /**
     * 
     * @param unknown $uid
     */
    static function getNewCorrectNum($uid, $f_catalog_id = NULL) {
        $redis = Yii::$app->cache;
        $rediskey = "ms:correct" . $uid;
        if ($f_catalog_id) {
            $rediskey = "ms:correct_" . $f_catalog_id . "_" . $uid;
        }

        //记录用户未读的批该信息id
        $ret = $redis->lrange($rediskey, 0, -1);
        if ($ret) {
            return count($ret);
        } else {
            return 0;
        }
    }

    /**
     * 提交批改请求时发推送消息
     * 推送消息发送的缓存服务器地址为cachequeue对应的服务器
     * @param unknown $fromid 发批改请求的学生uid
     * @param unknown $touid  被请求的老师的uid
     * @param unknown $correctid 批改id
     */
    static function submitPushMsg($from_uid, $to_uid, $correctid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::CORRECT_SUBMIT;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['correctid'] = $correctid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 求批改进入排行榜
     * @param unknown $from_uid
     * @param unknown $to_uid
     * @param unknown $correctid
     */
    static function rankPushMsg($from_uid, $to_uid, $tid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::CORRECT_RANK;
        $params['from_uid'] = $from_uid; //默认帮叔
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $tid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 完成批改后发推送消息
     * @param unknown $fromid 批改老师uid
     * @param unknown $touid  被批改学生的uid
     * @param unknown $correctid 批改id
     */
    static function finishPushMsg($from_uid, $to_uid, $correctid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::CORRECT_FINISH;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['correctid'] = $correctid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 老师拒批后，给学生发推送消息
     * @param unknown $from_uid
     * @param unknown $to_uid
     * @param unknown $correctid
     */
    static function refusePushMsg($from_uid, $to_uid, $correctid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::CORRECT_REFUSE;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['correctid'] = $correctid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 老师把求批改转作品后给学生发推送通知
     * @param unknown $from_uid
     * @param unknown $to_uid
     * @param unknown $correctid
     */
    static function changePushMsg($from_uid, $to_uid, $correctid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::CORRECT_CHANGE;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $correctid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 清除批改小红点的缓存
     * 访问单个详情页的时候删除correctid对应的信息
     * 访问列表页直接清除缓存
     * @param unknown $uid
     */
    static function clearRedCorrectNum($uid, $correctid = 0, $f_catalog_id = NULL) {
        $redis = Yii::$app->cache;
        $rediskey = "ms:correct" . $uid;
        if ($f_catalog_id) {
            $rediskey = "ms:correct_" . $f_catalog_id . "_" . $uid;
        }
        if ($correctid == 0) {
            $redis->delete($rediskey);
        } else {
            $redis->lrem($rediskey, 0, $correctid);
        }
    }

    /**
     * 得到批改过的老师列表
     * @return [type] [description]
     */
    public static function getHasCorrect($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select distinct teacheruid from ' . parent::tableName() . ' where submituid=' . $uid . ' and status=1 order by correct_time asc');
        $data = $command->queryAll();
        return $data;
    }

    /**
     * 获取批改老师列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getHasCorrectRedis($uid) {
        $redis = Yii::$app->cache;
        $rediskey = self::$has_correct_redis . $uid;
        // $redis->delete($rediskey);
        $correctids_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($correctids_arr)) {
            $correct = self::getHasCorrect($uid);
            if (count($correct) > 0) {
                foreach ($correct as $key => $value) {
                    $correctids_arr[] = $value['teacheruid'];
                    $ret = $redis->rpush($rediskey, $value['teacheruid'], true);
                }
                $redis->expire($rediskey, 3600 * 24 * 3);
            } else {
                return array();
            }

            return $correctids_arr;
        } else {
            return $correctids_arr;
        }
    }

    /**
     * 列表页获取信息
     * @param  [type] $correctid [description]
     * @return [type]            [description]
     */
    public static function getListDetailInfo($correctid) {
        $correct_info = CorrectService::getCorrectDetail($correctid);
        //未批改 显示得分数
        $correct_info['teacher_correct_score'] = CorrectRefuseReasonService::getCorrectScore($correct_info['correct_time'], $correct_info['ctime']);

        if (!empty($correct_info['source_pic_rid'])) {
            //原图
            $correct_info['source_pic'] = ResourceService::getResourceDetail($correct_info['source_pic_rid']);
            $correct_info['source_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 's');
            $correct_info['source_pic']['img']->l = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 'l');
        } else {
            $correct_info['source_pic'] = (object) null;
        }

        if (!empty($correct_info['correct_pic_rid'])) {
            //批改后图片
            $correct_info['correct_pic'] = ResourceService::getResourceDetail($correct_info['correct_pic_rid']);
            $correct_info['correct_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 's');
            $correct_info['correct_pic']['img']->l = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 'l');
        } else {
            $correct_info['correct_pic'] = (object) null;
        }
        $correct_info['submit_info'] = UserDetailService::getByUid($correct_info['submituid']);
        $correct_info['teacher_info'] = UserDetailService::getByUid($correct_info['teacheruid']);

        return $correct_info;
    }

    /**
     * 详情页获取信息
     * @param  [type] $correctid [description]
     * @param  [type] $uid       [description]
     * @return [type]            [description]
     */
    public static function getFullCorrectInfo($correctid, $uid) {
        $correct_info = self::getCorrectDetail($correctid);
        //主评论语音
        if (!empty($correct_info['majorcmt_id'])) {
            $correct_info['majorcmt'] = CorrectTalkService::getCorrectTalkDetail($correct_info['majorcmt_id']);
        } else {
            $correct_info['majorcmt'] = (object) null;
        }

        if (!empty($correct_info['source_pic_rid'])) {
            //原图
            $correct_info['source_pic'] = ResourceService::getResourceDetail($correct_info['source_pic_rid']);
            $correct_info['source_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 's');
            $correct_info['source_pic']['img']->l = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 'l');
        } else {
            $correct_info['source_pic'] = (object) null;
        }

        if (!empty($correct_info['correct_pic_rid'])) {
            //批改后图片
            $correct_info['correct_pic'] = ResourceService::getResourceDetail($correct_info['correct_pic_rid']);
            $correct_info['correct_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 's');
            $correct_info['correct_pic']['img']->l = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 'l');
        } else {
            $correct_info['correct_pic'] = (object) null;
            //兼容andriod2.3.8 bug，没有批改图会报错
            $correct_info['correct_pic'] = $correct_info['source_pic'];
        }

        //如果批改后没有示例图片 则随机取素材图片
        /* if($correct_info['status']==1 && empty($correct_info['example_pics'])){
          $correct_info['example_pics']=TweetService::getCorrectExampleImgRand();
          } */
        //示例图片
        if ($correct_info['example_pics']) {
            $example_pics_arr = explode(',', $correct_info['example_pics']);
            $example_pics = [];
            foreach ($example_pics_arr as $key1 => $value1) {
                //兼容有null的correctid的情况
                if ($value1 && is_numeric($value1)) {
                    $example_pics[] = ResourceService::getResourceDetail($value1);
                    $number = count($example_pics) - 1;
                    $example_pics[$number]['img']->t = CommonFuncService::getPicByType((array) $example_pics[$number]['img']->n, 't');
                }
            }
        } else {
            $example_pics = array();
        }
        $correct_info['example_pic'] = $example_pics;
        $example_pics = array();

        //图片上的语音
        $pointcmt_ids_arr = explode(',', $correct_info['pointcmt_ids']);
        if ($correct_info['pointcmt_ids']) {
            foreach ($pointcmt_ids_arr as $key1 => $value1) {
                $pointcmts_arr[] = CorrectTalkService::getCorrectTalkDetail($value1);
            }
        } else {
            $pointcmts_arr = array();
        }

        $correct_info['submit_info'] = UserDetailService::getByUid($correct_info['submituid']);
        $correct_info['teacher_info'] = UserDetailService::getByUid($correct_info['teacheruid']);
        //增加老师批改量字段      
        $o_teacher_info = UserCorrectService::getUserCorrectDetail($correct_info['teacheruid']);
        if ($o_teacher_info) {
            $correct_info['teacher_info'] = array_merge($correct_info['teacher_info'], $o_teacher_info);
        }
        //判断当前用户是老师还是学生 分享不同的网址
        $user_info = UserCorrectService::getUserCorrectDetail($uid);
        if (!empty($user_info)) {
            $correct_info['share_url'] = Yii::$app->params['sharehost'] . '/correct/share?correctid=' . $correct_info['correctid'] . '&user_type=teacher';
        } else {
            $correct_info['share_url'] = Yii::$app->params['sharehost'] . '/correct/share?correctid=' . $correct_info['correctid'] . '&user_type=submit';
        }
        //获取用户批改老师关注类型
        $correct_info['follow_type'] = UserRelationService::getBy2Uid($uid, $correct_info['submituid']);

        if ($correct_info['status'] == 1) {
            $correct_info['title'] = $correct_info['teacher_info']['sname'] . '老师批改的作品';
        } else {
            $correct_info['title'] = $correct_info['submit_info']['sname'] . '同学的作品';
        }
        $correct_info['correct_title'] = '已批改了' . $correct_info['submit_info']['sname'] . '的画作';
        $correct_info['pointcmt'] = $pointcmts_arr;
        if ($correct_info['correct_time']) {
            $correct_info['correct_time_format'] = CommonFuncService::format_time($correct_info['correct_time']);
        } else {
            $correct_info['correct_time_format'] = '';
        }

        $correct_info['ctime_format'] = CommonFuncService::format_time($correct_info['ctime']);
        $pointcmts_arr = array();
        $correct_info['fav'] = FavoriteService::getFavStatusByUidTid($uid, $correct_info['tid']);
        return $correct_info;
    }

    /**
     * 排行榜存缓存
     * @return [type] [description]
     */
    public static function getCorrectScoreRankRedis() {
        $rediskey = "correct_rank";
        $redis = Yii::$app->cache;
        $redis->delete($rediskey);
        $ranks = $redis->get($rediskey);
        if (empty($ranks)) {
            $ret_rank = self::getCorrectScoreRankDB();
            $redis->set($rediskey, $ret_rank);
            $redis->expire($rediskey, 60 * 60 * 3);
            return $ret_rank;
        }
        return $ranks;
    }

    /**
     * 数据库中得到批改得分排行榜
     * @return [type] [description]
     */
    public static function getCorrectScoreRankDB() {
        /* $ret = ['1' => "色彩", '4' => "素描", '5' => "速写"]; */
        $table = parent::tableName();
        $ret['f1'] = (new \yii\db\Query())->select("correctid")
                        ->from($table)
                        ->where(["status" => 1, "f_catalog_id" => 1])
                        ->andWhere([">", "ctime", time() - 24 * 60 * 60])
                        ->andWhere(["correct_fee"=>0])
                        ->orderBy("score desc")->limit(3)->all();

        $ret["f4"] = (new \yii\db\Query())->select("correctid")
                        ->from(parent::tableName())
                        ->where(["status" => 1, "f_catalog_id" => 4])
                        ->andWhere([">", "ctime", time() - 24 * 60 * 60])
                        ->andWhere(["correct_fee"=>0])
                        ->orderBy("score desc")->limit(3)->all();

        $ret["f5"] = (new \yii\db\Query())->select("correctid")
                        ->from(parent::tableName())
                        ->where(["status" => 1, "f_catalog_id" => 5])
                        ->andWhere([">", "ctime", time() - 24 * 60 * 60])
                        ->andWhere(["correct_fee"=>0])
                        ->orderBy("score desc")->limit(3)->all();

        $ret["f2"] = (new \yii\db\Query())->select("correctid")
                        ->from(parent::tableName())
                        ->where(["status" => 1, "f_catalog_id" => 2])
                        ->andWhere([">", "ctime", time() - 24 * 60 * 60])
                        ->andWhere(["correct_fee"=>0])
                        ->orderBy("score desc")->limit(3)->all();
        return json_encode($ret);
    }

    /**
     *
     * 获取日排行
     * @param unknown $type 类型id 素描 速写 色彩
     * @param unknown $rankType 1:日排行 2:周排行 
     * @param unknown $rn 记录个数
     * @param unknown $lastid 分页correctid
     * @param string $selfRank 是否获取自己的排名
     * @return
     */
    static function getRank($correctType, $rankType, $rn, $lastid, $uid, $selfRank = false) {
        $rediskey = "correct_rank_" . $rankType . "_" . $correctType;
        $redis = Yii::$app->cache;
        $sTime = time();

        if ($rankType == 1) {
            $sTime -= 3600 * 24;  //日排行
        } else {
            $sTime -= 3600 * 24 * 7; //周排行
        }
        //获取全部id
        $alldata = $redis->lrange($rediskey, 0, -1);
        if (!$alldata) {
            //没获取到则需要建立对应的缓存
            $alldata = static::initRankCacheFromDB($correctType, $sTime, $rediskey);
        }
        //获取返回结果起始值
        if ($lastid == 0) {
            $index = 0;
        } else {
            $index = array_search($lastid, $alldata) + 1;
        }
        //获取返回数据
        $ret['data'] = [];
        $totalcount = count($alldata);

        for ($i = $index; $i < $index + $rn; $i++) {
            if ($i <= $totalcount - 1) {
                $ret['data'][] = $alldata[$i];
            }
        }
        //判断是否要获取个人排名
        if ($selfRank) {
            $rediskeyself = $rediskey . '_self';
            $selfrank = $redis->hget($rediskeyself, $uid);
            if ($selfrank) {
                $tmp['rank'] = $selfrank;
                $tmp['correctid'] = $alldata[$selfrank - 1];
                //如果自身排名不在第一页，则加入自身排名
                if (!in_array($tmp['correctid'], $ret['data'])) {
                    $ret['self'] = $tmp;
                }
            }
        }
        return $ret;
    }

    /**
     *
     * 获取日排行
     * @param unknown $type 类型id 素描 速写 色彩
     * @param unknown $rankType 1:日排行 2:周排行 
     * @param unknown $rn 记录个数
     * @param unknown $lastid 分页correctid
     * @param string $selfRank 是否获取自己的排名
     * @return
     */
    static function getNewRank($correctType, $rankType, $rn, $lastid = 0, $uid = '', $selfRank = false, $timestamp = '', $year = '', $status = '') {
        $redis = Yii::$app->cache;
        $rediskey = "correct_rank_" . $correctType . "_" . $rankType . "_" . $year . '_' . $timestamp;
        //新接口传递年
        $sTime = self::getTimeData($rankType, $year, $timestamp);
        //获取全部id
        $alldata = $redis->lrange($rediskey, 0, -1);

        //没获取到则需要建立对应的缓存
        if (!$alldata) {
            $alldata = static::initRankCacheNew($correctType, $sTime, $rediskey, $year, $rankType, $status);
        }
        //获取返回结果起始值
        if ($lastid == 0) {
            $index = 0;
        } else {
            $index = array_search($lastid, $alldata) + 1;
        }
        //获取返回数据
        $ret['data'] = [];
        $totalcount = count($alldata);
        for ($i = $index; $i < $index + $rn; $i++) {
            if ($i <= $totalcount - 1) {
                $ret['data'][] = $alldata[$i];
            }
        }
        return $ret;
    }

    //获取当前月当前日当前周
    public static function getCurrentTime($rankType, $timestamp, $year) {
        $value = 1;
        #1日榜 2周榜 3月榜单
        switch ($rankType) {
            case 1:#1日榜 
                if ($timestamp == strtotime(date('Y-m-d')) && $year == date('Y')) {
                    $value = 2;
                }
                break;
            case 2:#2周榜
                if ($timestamp == date("W") && $year == date('Y')) {
                    $value = 2;
                }
                break;
            case 3:#3月榜单
                if ($timestamp == date("m") && $year == date('Y')) {
                    $value = 2;
                }
                break;
        }
        return $value;
    }

    //时间划分
    public static function getTimeData($rankType, $year, $timestamp) {
        $sTime = '';
        if ($rankType == 1) {
            $sTime = $timestamp;  //日排行
        } else if ($rankType == 2) {
            foreach (CommonFuncService::getWeek($year) as $key => $val) {
                if ($timestamp == $key) {
                    $sTime = $val;
                }
            }
            # $sTime -= 3600 * 24 * 7; //周排行
        } else if ($rankType == 3) {
            $sTime = CommonFuncService::mFristAndLast($year, $timestamp); //年 月
        }


        return $sTime;
    }

    /**
     * 按时间和类型获取排行榜correctid和用户id，按照得分倒序排列
     * @param unknown $correctType
     * @param unknown $sTime
     */
    static function initRankCacheNew($correctType, $sTime, $rediskey, $year = '', $rankType = '', $status = '') {
        $redis = Yii::$app->cache;
        $table = parent::tableName();
        $ids = (new \yii\db\Query())->select(["correctid", "submituid"])
                ->from($table)
                ->where(["status" => 1, "f_catalog_id" => $correctType])
                ->andWhere(["correct_fee" => 0]);

        if ($year) {
            if ($rankType == 1) {
                //当日的时间
                $ids->andWhere([">", "ctime", $sTime])->andWhere(["<", "ctime", $sTime + 86400]);
                //周
            } else if ($rankType == 2) {
                $ids->andWhere([">", "ctime", $sTime[0]])->andWhere(["<", "ctime", $sTime[1] + 86400]);
                //月
            } else if ($rankType == 3) {
                $ids->andWhere([">", "ctime", $sTime['firstday']])->andWhere(["<", "ctime", $sTime['lastday']]);
            }
        } else {
            $ids->andWhere([">", "ctime", $sTime]);
        }
        $ids = $ids->andWhere([">", "score", 0])->orderBy("score desc,ctime desc")->all(); #;//->createCommand()->getRawSql();
        //存缓存，每个用户只存一条排行
        $arruser = [];
        $ret = [];
        //用户排名缓存需要同时重建
        //$userRank = 1;
        //if (!$status) {
        //   $rediskeyself = $rediskey . '_self';
        //   $redis->delete($rediskeyself);
        //}
        foreach ($ids as $k => $v) {
            if (!in_array($v['submituid'], $arruser)) { //排重
                $arruser[] = $v['submituid'];
                #if (!$status) {
                $redis->rpush($rediskey, $v['correctid']);
                //记录用户排名
                //$redis->hset($rediskeyself, $v['submituid'], $userRank);
                //}
                //$userRank += 1;
                $ret[] = $v['correctid'];
            }
        }
        //if (!$status) {
        //缓存半小时
        $redis->expire($rediskey, 1800);
        //个人排名缓存时间要大于id缓存
        //$redis->expire($rediskeyself, 3600);
        //}
        return $ret;
    }

    /**
     * 按时间和类型获取排行榜correctid和用户id，按照得分倒序排列
     * @param unknown $correctType
     * @param unknown $sTime
     */
    static function initRankCacheFromDB($correctType, $sTime, $rediskey) {
        $redis = Yii::$app->cache;
        $table = parent::tableName();
        $ids = (new \yii\db\Query())->select(["correctid", "submituid"])
                ->from($table)
                ->where(["status" => 1, "f_catalog_id" => $correctType])
                ->andWhere([">", "ctime", $sTime])
                ->andWhere([">", "score", 0])
                ->orderBy("score desc")
                ->all();
        //存缓存，每个用户只存一条排行
        $arruser = [];
        $ret = [];
        //用户排名缓存需要同时重建
        $userRank = 1;
        $rediskeyself = $rediskey . '_self';
        $redis->delete($rediskeyself);
        foreach ($ids as $k => $v) {
            if (!in_array($v['submituid'], $arruser)) { //排重
                $arruser[] = $v['submituid'];
                $redis->rpush($rediskey, $v['correctid']);
                //记录用户排名
                $redis->hset($rediskeyself, $v['submituid'], $userRank);
                $userRank += 1;
                $ret[] = $v['correctid'];
            }
        }
        //缓存半小时
        $redis->expire($rediskey, 1800);
        //个人排名缓存时间要大于id缓存
        $redis->expire($rediskeyself, 3600);
        return $ret;
    }

    /**
     * 分享操作完成后，写cache调用后台进程去执行amr转mp3操作
     * @param unknown $from_uid
     * @param unknown $to_uid
     * @param unknown $correctid
     */
    static function shareTaskCache($correctid) {
        $rediskey = 'correcttask';
        $redis = Yii::$app->cachequeue;

        $params['tasktype'] = 'share';
        $params['correctid'] = $correctid;
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 获取详情页推荐批改id列表
     * 规则为同类型80以上分数的批改
     * @param unknown $correctid
     * @param unknown $f_catalog_id
     * @param unknown $s_catalog_id
     * @param unknown $limit
     */
    static function getRecommendIdsByCorrectId($correctid, $f_catalog_id, $s_catalog_id, $limit) {
        $ids = static::find()->select(['correctid'])
                ->where(['<', 'correctid', $correctid])
                ->andWhere(['status' => 1])
                ->andWhere(['f_catalog_id' => $f_catalog_id])
                ->andWhere(['s_catalog_id' => $s_catalog_id])
                ->andWhere(['>=', 'score', 83])
                ->andWhere(["correct_fee"=>0])
                ->orderBy('correctid desc')
                ->limit($limit)
                ->all();
        if ($ids) {
            $ret = null;
            foreach ($ids as $id) {
                $ret[] = $id['correctid'];
            }
            return $ret;
        }
        return null;
    }

    /**
     * 获取老师未批改作品的数量 pay_type 0=>全部未批改 1=>免费批改 2=>付费批改
     * @param unknown $teacherUid
     */
    static function getWaitCorrectCount($teacherUid,$pay_type=0) {
        $models = (new \yii\db\Query())
                ->select(['teacheruid'])
                ->from(parent::tableName())
                ->where(['teacheruid' => $teacherUid])
                ->andWhere(['status' => 0]);
        if($pay_type==1){
            //免费批改
            $models =$models->andWhere(['=','correct_fee',0]);
        }elseif($pay_type==2){
            //付费批改
             $models =$models->andWhere(['>','correct_fee',0])->andWhere(['pay_status'=>1]);
        }
        $models = $models->all();
        if ($models) {
            return count($models);
        }
        return 0;
    }

    /**
     * 根据类型获取用户最新被批改的贴子
     * @param unknown $uid
     * @param number $limit
     * @return unknown
     */
    static function getRecentUserCorrectidByMaintype($uid, $maintype, $limit) {
        $ret = (new \yii\db\Query())
                        ->select('correctid')
                        ->from(parent::tableName())
                        ->where(['submituid' => $uid])
                        ->andWhere(['status' => 1])
                        ->andWhere(['f_catalog_id' => $maintype])
                        ->limit($limit)
                        ->orderBy('correctid DESC')->all();
        return $ret;
    }

    /**
     * 获取用户某一个类型下某一个时间段的最后几次打分，用于计算能力模型
     * @param unknown $uid
     * @param unknown $catalogid
     * @param unknown $stime
     * @param unknown $etime
     * @param number $limit
     * @return unknown
     */
    static function getUserLastCorrectMark($uid, $catalogid, $stime, $etime, $limit = 0) {
        $ret = (new \yii\db\Query())
                        ->select('markdetail')
                        ->from(parent::tableName())
                        ->where(['submituid' => $uid])
                        ->andWhere(['status' => 1])
                        ->andWhere(['f_catalog_id' => $catalogid])
                        ->andWhere('markdetail is not null')
                        ->andWhere(['>', 'ctime', $stime])
                        //->andWhere(['<=','ctime',$etime]) 因为老师不一定按照顺序批改，所以去掉了截至日期，保证能够有数据
                        ->limit($limit)
                        ->orderBy('correctid DESC')->all();
        return $ret;
    }

    /**
     * 缓存获取分类批改列表
     * @param  [type]  $uid          [description]
     * @param  [type]  $f_catalog_id [description]
     * @param  integer $s_catalog_id [description]
     * @param  [type]  $lastid       [description]
     * @param  integer $rn           [description]
     * @return [type]                [description]
     */
    public static function getCorrectListByCatalogRedis($uid, $f_catalog_id, $s_catalog_id = 0, $lastid = NULL, $rn = 10) {
        //批改当前状态 0未批改  1批改完成  2已撤销 3拒批改
        $query = self::find()->select("correctid")->where(['status' => 0])->orWhere(['status' => 1])->orWhere(['status' => 3])->andWhere(['submituid' => $uid])->andWhere("(correct_fee=0 or (correct_fee>0 and pay_status=1 ))");
        if ($f_catalog_id) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }

        if ($lastid) {
            $query->andWhere(['<', 'correctid', $lastid]);
        }
        $correct_arr = $query->limit($rn)->orderBy("correctid desc")->all();
        $correctids = [];
        if ($correct_arr) {
            foreach ($correct_arr as $key => $value) {
                $correctids[] = $value['correctid'];
            }
        }
        return $correctids;
    }

    /**
     * 数据库获取对应一二级分类数据
     * @param  [type] $uid          [description]
     * @param  [type] $f_catalog_id [description]
     * @param  [type] $s_catalog_id [description]
     * @return [type]               [description]
     */
    public static function getCorrectListByCatalogDb($uid, $f_catalog_id, $s_catalog_id = 0) {
        //批改当前状态 0未批改  1批改完成  2已撤销 3拒批改
        $query = self::find()->select("correctid")->where(['status' => 0])->orWhere(['status' => 1])->andWhere(['submituid' => $uid]);
        if ($f_catalog_id) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        return $query->orderBy("correctid desc")->all();
    }

    /**
     * 取出 已/未 批改过的列表 
     * @param int $resourceid
     * @param int $type  0未批改  1批改完成  2已撤销 3拒批改
     * @return array 返回已经批改过的信息 [list]
     */
    public static function getCorrectSuccess($resourceid, $type = 1, $status = 0) {
        if ($status) {
            return self::find()->select("tid")->where(['status' => $type])->andWhere(['source_pic_rid' => $resourceid])->asArray()->all();
        } else {
            return self::find()->select("*")->where(['status' => $type])->andWhere(['source_pic_rid' => $resourceid])->count();
        }
    }

    /**
     * 获取传递参数
     * @param int $year        年
     * @param int $rankType    类型
     * @param int $timestamp   时间值
     */
    public static function getUserRank($year, $rankType = '', $timestamp, $uid, $correctType, $my_score = '') {
        $connection = \Yii::$app->db;
        $sTime = self::getTimeData($rankType, $year, $timestamp);
        if ($rankType == 1) {
            //今天的时间
            $start_time = $sTime; #开始时间
            $end_time = $sTime + 86400; #结束时间
            //昨天的时间
            $past_start_time = $start_time - 86400;
            $past_end_time = $start_time;
        } else if ($rankType == 2) {
            $start_time = $sTime['0'];
            $end_time = $sTime['1'] + 86400;
            //上一周的第一天
            $past_start_time = $start_time - 604800;
            $past_end_time = $start_time;
        } else if ($rankType == 3) {
            //获取本月份的数据
            $start_time = $sTime['firstday'];
            $end_time = $sTime['lastday'] + 1;
            if ($timestamp > 1) {//如果在同一年可以获取本年分的月与本月对不
                $s = self::getTimeData(3, $year, $timestamp - 1);
                $past_start_time = $s['firstday'];
                $past_end_time = $s['lastday'] + 1;
            } else {
                //如果是本年份的一月，只能获取上一年最后一个月来对比
                $old_year = $year - 1;
                $timestamp = 12;
                $s = self::getTimeData(3, $old_year, $timestamp);
                $past_start_time = $s['firstday'];
                $past_end_time = $s['lastday'] + 1;
            }
        }
        $data = [];
        $data['score_rank_past'] = '';
        $data['score_rank_new'] = '';
        //指定时间段断的最高分数
        $score = CorrectService::find()->select("MAX(score) as score")->andWhere(['status' => 1])
                        ->andWhere(['f_catalog_id' => $correctType])
                        ->andWhere(['>', 'ctime', $start_time])
                        ->andWhere(['<', 'ctime', $end_time])
                        ->andWhere(['submituid' => $uid])->asArray()->one();
        $data['max_score'] = (int) $score['score'];
        //如果当期有数据显示，说明本日，本周，本月才有排名
        if ($data['max_score']) { //,count(*) as count,submituid
            //最新排名
            $score_rank_new = self::find()->select("MAX(score) as score")->andWhere(['status' => 1])
                            ->andWhere(['f_catalog_id' => $correctType])
                            ->andWhere(['>', 'ctime', $start_time])
                            ->andWhere(['<', 'ctime', $end_time])
                            ->groupby('submituid')
                            ->having(['>', 'score', $data['max_score']])
                            ->orderby('score desc,ctime desc ')//->createCommand()->getRawSql();
                            ->asArray()->all();


            //最新排名如果为空，说明自己排名为第一名
            if (count($score_rank_new) == 0) {
                $data['score_rank_new'] = 1;
            } else {
                $data['score_rank_new'] = count($score_rank_new) + 1;
            }
            $command_count = self::find()->select("MAX(score) as score")->andWhere(['status' => 1])
                            ->andWhere(['f_catalog_id' => $correctType])
                            ->andWhere(['>', 'ctime', $start_time])
                            ->andWhere(['<', 'ctime', $end_time])
                            ->andWhere(['>', 'score', 0])
                            ->groupby('submituid')
                            ->orderby('score desc ')
                            ->asArray()->all();
            //排名第一
            if ($data['score_rank_new'] == 1) {
                $data['out_rand'] = 1;
            } else {
                //小于第一名的时候，才可以计算碾压率
                $data['out_rand'] = (count($command_count) - $data['score_rank_new']) / count($command_count); //今天总数-我的排名/今天总数
            }
        }
        $past_score = CorrectService::find()->select("MAX(score) as score")->andWhere(['status' => 1])
                        ->andWhere(['f_catalog_id' => $correctType])
                        ->andWhere(['>', 'ctime', $past_start_time])
                        ->andWhere(['<', 'ctime', $start_time])
                        ->andWhere(['submituid' => $uid])->asArray()->one();
        $max_past_score = $past_score['score'];
        if ($max_past_score) { //,count(*) as count,submituid
            //上一次排名 【前天，上一周，上一月】
            $past_rank = self::find()->select("MAX(score) as score")->andWhere(['status' => 1])
                            ->andWhere(['f_catalog_id' => $correctType])
                            ->andWhere(['>', 'ctime', $past_start_time])
                            ->andWhere(['<', 'ctime', $past_end_time])
                            ->groupby('submituid')
                            ->having(['>', 'score', $max_past_score])
                            ->orderby('score desc,ctime desc ')
                            ->asArray()->all();

            if (count($past_rank) == 0) {
                $data['score_rank_past'] = 1;
            } else {
                $data['score_rank_past'] = count($past_rank) + 1;
            }
        }
        if ($data['score_rank_past']) {
            $data['rand_forward'] = $data['score_rank_past'] - $data['score_rank_new']; //昨天的减去今天的 = 进步的名称
        } else {
            $data['rand_forward'] = 0;
        }
        $data['correctid'] = '';
        if (!$my_score) {
            $data['correctid'] = self::getUserCorrect($uid, $data['max_score'], $correctType, $start_time, $end_time)['correctid'];
        }
        return $data;
    }

    /**
     * 获取用户在这段时间内的批改数
     * @param type $year
     * @param type $rankType
     * @param type $timestamp
     * @param type $uid
     * @param type $correctType
     */
    public static function getUserCorrectNum($uid) {
        $correct_count = CorrectService::find()->where(['submituid' => $uid])->andWhere(['status' => 1])->count();
        return (int) $correct_count;
    }

    /**
     * 
     * @param type $uid
     * @param type $score
     */
    public static function getUserCorrect($uid, $score, $correctType, $start_time, $end_time) {
//        return self::find()->select(['correctid'])->where(['submituid' => $uid])->andWhere(['status' => 1])->andWhere(['score' => $score])->andWhere(['f_catalog_id' => $correctType])
//               ->andWhere(['>', 'ctime', $start_time])->andWhere(['<', 'ctime', $end_time])
//               ->orderBy('score desc,ctime desc')->asArray()->one();
        $rediskey = "user_cache_" . $uid . '_' . $correctType;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::find()->select(['correctid'])->where(['submituid' => $uid])->andWhere(['status' => 1])->andWhere(['score' => $score])->andWhere(['f_catalog_id' => $correctType])
                            ->andWhere(['>', 'ctime', $start_time])->andWhere(['<', 'ctime', $end_time])
                            ->orderBy('score desc,ctime desc')->asArray()->one();
            if ($detail) {
                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 1800);
            }
        }
        return $detail;
    }

    /**
     * 获取老师已批改数量
     * @param type $courseid
     * @param type $uid
     * @return type
     */
    public static function getCourseRecommendNum($courseid, $uid) {
        return CorrectService::find()->where("FIND_IN_SET(:courseid,recommend_courseids)", ['courseid' => $courseid])->andWhere(['submituid' => $uid])->count();
    }

    /**
     * @desc   获取用户所有的批改
     * @param  int $uid 用户id
     * @return int $number
     */
    public static function getAllUserCorrect($uid) {
        return CorrectService::find()->where(['status' => 1])->andWhere(['submituid' => $uid])->count();
    }

    /**
     * @desc   获取用户被指定老师所批改的改画
     * @param  int $uid 用户id
     * @return int $number
     */
    public static function getTeacherUserFinisheCorrect($uid, $teacher, $last_tid, $limit) {
        //判断是否是下拉
        if ($last_tid == 0) {
            $mark = ">";
        } else {
            $mark = "<";
        }
        $query = new \yii\db\Query();
        return $query->select("correctid")->from(parent::tableName())->where([$mark, 'correctid', $last_tid])->andWhere(['teacheruid' => $teacher,'submituid' => $uid,'status' => 1])->orderBy('correctid desc')->limit($limit)->all();
    }

    /**
     * 获取榜单入口排名
     */
    public static function getDayMax() {
        #$f_catalog_id = [1,4,5];
        # foreach($f_catalog_id as $key=>$v){
        $array = self::getNewRank(1, 1, 1);
        # }
        print_r($array);
    }
    /**
     * 更改支付状态
     * @param  [type] $correctid [description]
     * @return [type]            [description]
     */
    public static function updateBuyStatus($correctid,$fee){
        $correct_info=self::find()->where(['correctid'=>$correctid])->one();
        $correct_info->pay_status=1;
        $correct_info->correct_fee=intval($fee);
        $ret=$correct_info->save();
        if($ret){
            self::submitPushMsg($correct_info->submituid,$correct_info->teacheruid,$correctid);
        }
        

    }
}
