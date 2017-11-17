<?php

namespace api\service;

use common\models\myb\Course;
use Yii;
use common\redis\Cache;
use api\service\CourseSectionService;
use api\service\UserDetailService;
use common\service\dict\CourseDictDataService;
use api\service\FavoriteService;
use api\service\UserRelationService;
use common\service\DictdataService;
use api\service\OrderinfoService;
use api\service\CorrectService;
use api\service\CourseSharelottoService;
use api\service\GroupBuyService;
use api\service\VideoResourceService as VideoResource;

/**
 * 直播相关方法
 */
class CourseService extends Course {

    /**
     * 获取详情
     */
    public static function getDetail($courseid, $uid = -1) {
        $rediskey = "course_detail_" . $courseid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::find()->where(['courseid' => $courseid])->asArray()->one();

            if ($detail) {
                //浏览量处理
                $detail['hits'] = intval($detail['hits']) + intval($detail['hits_basic']);
                //一二级分类处理
                $detail['f_catalog'] = CourseDictDataService::getCourseMainTypeNameById($detail['f_catalog_id']);
                $detail['s_catalog'] = CourseDictDataService::getCourseSubTypeById($detail['f_catalog_id'], $detail['s_catalog_id']);
                //课程总时长
                $detail['video_legth'] = self::getCourseVideoLength($courseid);
                $detail['video_price'] = self::getCourseVideoPrice($courseid);
                $detail['ios_video_price'] = self::getCourseVideoPrice($courseid, 2);
                $detail['total_bounty_fee_ios'] = self::getCourseVideoBounty($courseid, 2);
                $detail['total_bounty_fee'] = self::getCourseVideoBounty($courseid);

                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }
        }
        //获取试学课程
        $detail['learn_video'] = (object)[];
        if ($detail['learn_videoid']) {
            $detail['learn_video'] = (object)VideoResource::getDetail($detail['learn_videoid']);
        }
        //客服咨询
        $detail['customer_service'] = (object) json_decode($detail['customer_service'], 1);

        //计算课程被老师推荐给了用户多少次
        if ($uid) {
            $detail['course_recommend_num'] = CorrectService::getCourseRecommendNum($courseid, $uid);
        }
        $detail['productid'] = DictdataService::getIosProductidByPrice($detail['course_price_ios']);
        $detail['buy_status'] = OrderinfoService::getByGoodStatus($uid, 2, $courseid);
        //更改页面样式
        $detail['content'].='<style type="text/css">img{width: 100%;}p{margin:15px 0;}</style>';
        $detail['userinfo'] = UserDetailService::getByUid($detail['teacheruid']);
        //课程粉丝数
        $detail['userinfo']['course_num'] = self::getUserCourseCount($detail['teacheruid']);
        $detail['userinfo']['follower_num'] = UserRelationService::getFollowerNum($detail['teacheruid']);
        $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['courseid'], 7);

        $favinfo=FavoriteService::getFavInfoByContent($detail['courseid'],7);
        $detail=array_merge($detail,$favinfo);


        //返回团购信息
        $groupbuy =GroupBuyService::getGroupBuyInfo($courseid);
        if($groupbuy){
            //是否参加团购
            $groupbuy['is_join'] = OrderinfoService::getGroupByStatus($groupbuy['groupbuyid'],$uid);
            //团购结束前不可观看视频
            if($groupbuy['is_join'] && time()>=$groupbuy['start_time'] && time()<=$groupbuy['end_time']){
                $detail['buy_status'] = 1;
            }
            if($detail['buy_status'] == 1){
                $detail['groupbuy'] = $groupbuy;
            }
        }
        
        $detail['share_url'] = Yii::$app->params['sharehost'] . "/course/detail?courseid=" . $detail['courseid'];
        return $detail;
    }

    /**
     * 缓存获取一二级分类对应的列表
     * @param  [type]  $f_catalog_id [description]
     * @param  [type]  $s_catalog_id [description]
     * @param  [type]  $lastid       [description]
     * @param  integer $rn           [description]
     * @return [type]                [description]
     */
    public static function getCourseList($f_catalog_id, $s_catalog_id, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "course_list_" . $f_catalog_id . "_" . $s_catalog_id;
        //$redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getCourseListDb($f_catalog_id, $s_catalog_id);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['courseid'] . ',';
                $ret = $redis->rpush($rediskey, $value['courseid'], true);
            }
            $redis->expire($rediskey, 3600 * 3);
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
     * 数据库获取列表
     * @param  [type] $f_catalog_id [description]
     * @param  [type] $s_catalog_id [description]
     * @return [type]               [description]
     */
    public static function getCourseListDb($f_catalog_id, $s_catalog_id) {
        $query = self::find()->select('courseid')->where(['status' => 2])->andWhere(['f_catalog_id' => $f_catalog_id]);

        if (intval($s_catalog_id) > 0) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        return $query->orderBy("ctime desc")->all();
    }

    /**
     * 通过课程id数组获取列表详情信息 
     * @param  [type] $arr [1,2,3]
     * @return [type]      [description]
     */
    public static function getListDetail($arr, $uid = '') {
        $return_list = [];
        if ($arr) {
            foreach ($arr as $key => $value) {
                $return_list[] = self::getDetail($value, $uid);
            }
        }

        return $return_list;
    }

    /**
     * 缓存获取老师课程id列表
     * @param  [type]  $uid    [description]
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getTeacherCourseList($uid, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "teacher_course_list" . $uid;
        // $redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getTeacherCourseListDb($uid);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['courseid'] . ',';
                $ret = $redis->rpush($rediskey, $value['courseid'], true);
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
     * 数据库获取老师课程列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getTeacherCourseListDb($uid) {
        return self::find()->select("courseid")->where(['status' => 2])->andWhere(['teacheruid' => $uid])->orderBy("courseid desc")->all();
    }

    public static function getFullCourseInfo($courseid, $uid = -1) {
        $course_info = self::getDetail($courseid, $uid);
        //获取课程下章节详情
        $course_section_ids = CourseSectionService::getCourseSectionList($courseid);
        $sectioin_info = CourseSectionService::getCourseSectionListDetail($course_section_ids, $uid);
        //返回课程视频个数
        $video_count = 0;
        foreach ($sectioin_info as $key => $value) {
            $video_count+=count($value['videos']);
        }
        $course_info['sectioin'] = $sectioin_info;
        $course_info['video_count'] = $video_count;
       
        return $course_info;
    }

    /**
     * 得到用户课程数
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCourseCount($uid) {
        return self::find()->where(['status' => 2])->andWhere(['teacheruid' => $uid])->count();
    }

    /**
     * 得到课程视频长度
     * @return [type] [description]
     */
    public static function getCourseVideoLength($courseid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select sum(video_length) as video_lengths from myb_video_resource where videoid in (select videoid from myb_course_section_video where sectionid in (select sectionid from myb_course_section where courseid=' . $courseid . ' and status=1) and status=1);');
        $data = $command->queryAll()[0]['video_lengths'];
        return $data;
    }

    /**
     * 得到课程视频价格
     * @return [type] [description]
     */
    public static function getCourseVideoPrice($courseid, $type = 1) {
        if ($type == 1) {
            $pricekey = "sale_price";
        } else {
            $pricekey = "ios_price";
        }
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select sum(' . $pricekey . ') as sale_price  from myb_course_section_video where sectionid in (select sectionid from myb_course_section where courseid=' . $courseid . ' and status=1 ) and status=1;');
        $data = $command->queryAll()[0]['sale_price'];
        return $data;
    }

    /**
     * 得到分节购买课程佣金价格
     * @return [type] [description]
     */
    public static function getCourseVideoBounty($courseid, $type = 1) {
        if ($type == 1) {
            $pricekey = "bounty_fee";
        } else {
            $pricekey = "bounty_fee_ios";
        }
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select sum(' . $pricekey . ') as bounty  from myb_course_section_video where sectionid in (select sectionid from myb_course_section where courseid=' . $courseid . ' and status=1 ) and status=1;');
        $data = $command->queryAll()[0]['bounty'];
        return $data;
    }

    /**
     * 增加浏览量
     * @param [type] $courseid [description]
     */
    public static function addHits($courseid) {
        $model = self::find()->where(['courseid' => $courseid])->one();
        $model->hits = $model->hits + 1;
        $ret = $model->save();
        if ($ret) {
            $redis = Yii::$app->cache;
            $redis_key = 'course_detail_' . $courseid;
            $redis->hincrby($redis_key, 'hits', 1);
        }
    }

    /**
     * 根据一二级分类随机获取指定数量课程
     * @param  [type] $course_search_catalog [description]
     * @param  [type] $limit                 [description]
     * @return [type]                        [description]
     */
    public static function getCourseByCatalogRand($course_search_catalog) {
        $courseids = [];
        if ($course_search_catalog) {
            foreach ($course_search_catalog as $key => $value) {
                $scourseid=self::getLateWeekCourse($value['f_catalog_id'],$value['s_catalog_id'],$value['limit']);
                if(!$scourseid || count($scourseid)<$value['limit']){
                    $scourseid = self::find()->select('courseid')->where(['status' => 2])->andWhere(['f_catalog_id' => $value['f_catalog_id']])->andWhere(['s_catalog_id' => $value['s_catalog_id']])->limit($value['limit'])->orderBy("rand()")->all();
                }
                foreach ($scourseid as $key1 => $value1) {
                    $courseids[] = $value1['courseid'];
                }
            }
        }
        return self::getListDetail($courseids);
    }
    /**
     * 获取最近一周最新的课程
     * @param  [type] $f_catalog_id [description]
     * @param  [type] $s_catalog_id [description]
     * @param  [type] $limit        [description]
     * @return [type]               [description]
     */
    public static function getLateWeekCourse($f_catalog_id,$s_catalog_id,$limit){
        $time_create=time()-60*60*24*7;
        $scourseid = self::find()->select('courseid')->where(['status' => 2])->andWhere(['f_catalog_id' => $f_catalog_id])->andWhere(['s_catalog_id' => $s_catalog_id])->andWhere(['>',"ctime",$time_create])->limit($limit)->orderBy("courseid desc")->all();
        return $scourseid;
    }
    /**
     * 根据分类获取课程
     * @return [type] [description]
     */
    public static function getCourseidByCatalog($f_catalog_id = 0, $s_catalog_id = 0, $type = "new", $limit = 2) {
        $query = self::find()->select("courseid")->where(['status' => 2]);
        if (intval($f_catalog_id) > 0) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if (intval($s_catalog_id) > 0) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        if ($type == "new") {
            $query->orderBy("courseid desc");
        } else if ($type == "rand") {
            $query->orderBy("rand()");
        }
        $lievids = $query->limit($limit)->all();
        $ret_courseids = [];
        foreach ($lievids as $key => $value) {
            $ret_courseids[] = $value['courseid'];
        }
        return $ret_courseids;
    }

    /**
     * 缓存读取分类获取课程
     * @param  integer $f_catalog_id [description]
     * @param  integer $s_catalog_id [description]
     * @return [type]                [description]
     */
    public static function getCourseidByCatalogRedis($f_catalog_id = 0, $s_catalog_id = 0) {
        $redis = Yii::$app->cache;
        $rediskey = "new_course_catalog" . $f_catalog_id . $s_catalog_id;
        //$redis->delete($rediskey);
        $courseids = $redis->get($rediskey);
        if (empty($courseids)) {
            //一二级分类获取
            $courseids = self::getCourseidByCatalog($f_catalog_id, $s_catalog_id, "rand", 2);
            if (count($courseids < 2)) {
                $courseids = self::getCourseidByCatalog($f_catalog_id);
            }
            if ($courseids) {
                $courseids = json_encode($courseids);
                $redis->set($rediskey, $courseids);
                $redis->expire($rediskey, 3600);
            } else {
                $courseids = json_encode($courseids);
            }
        }
        return json_decode($courseids);
    }

    /**
     * 缓存获取批改推荐 一个付费一个免费
     * @param  [type] $correctid  [description]
     * @param  [type] $maintypeid [description]
     * @param  [type] $subtypeid  [description]
     * @return [type]             [description]
     */
    public static function getCourseByCorrectidRedis($correctid, $maintypeid, $subtypeid) {
        $redis = Yii::$app->cache;
        $rediskey = "new_course_correctid" . $correctid;
        //$redis->delete($rediskey);
        $courseids = $redis->get($rediskey);
        if (empty($courseids)) {
            //一二级分类获取
            $courseids = self::getCourseidByCorrectid($maintypeid, $subtypeid);
            if ($courseids) {
                $courseids = json_encode($courseids);
                $redis->set($rediskey, $courseids);
                $redis->expire($rediskey, 3600);
            } else {
                $courseids = json_encode($courseids);
            }
        }
        return json_decode($courseids);
    }

    /**
     * 获取批改推荐课程  付费免费个一个
     * @param  [type] $maintypeid [description]
     * @param  [type] $subtypeid  [description]
     * @return [type]             [description]
     */
    public static function getCourseidByCorrectid($maintypeid, $subtypeid) {
        //获取对应一二级分类付费课程id
        $buy_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, $subtypeid, 1);
        if (empty($buy_courseids)) {
            //获取对应一二级分类免费课程
            $buy_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, $subtypeid, 0);
            if (empty($buy_courseids)) {
                //获取对应一级分类付费课程
                $buy_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, 0, 1);
                if (empty($buy_courseids)) {
                    //获取对应一级分类免费课程
                    $buy_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, 0, 0);
                }
            }
        }
        //获取对应一二级分类免费课程
        $free_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, $subtypeid, 0, 1, $buy_courseids);
        if (empty($free_courseids)) {
            //获取对应一级分类免费课程
            $free_courseids = CourseSectionVideoService::getIsPrizeCourseid($maintypeid, 0, 0, 1, $buy_courseids);
        }
        //合并付费免费内容
        $courseids = array_merge($buy_courseids, $free_courseids);
        return $courseids;
    }

    /**
     * 获取课程下面的推荐课程
     * 
     */
    public static function getRelatedCourse($courseid, $maintypeid, $subtypeid, $uid = -1) {
        $redis = Yii::$app->cache;
        $rediskey = "related_course_" . $courseid;
        $redis->delete($rediskey);
        $courseids = $redis->get($rediskey);
        if (empty($courseids)) {
            //一二级分类获取
            $courseids = self::getCourseidByCorrectid($maintypeid, $subtypeid);
            if ($courseids) {
                $courseids = json_encode($courseids);
                $redis->set($rediskey, $courseids);
                $redis->expire($rediskey, 3600);
            } else {
                $courseids = json_encode($courseids);
            }
        }
        return self::getListDetail(json_decode($courseids), $uid);
    }

    /**
     * 缓存获取一二级分类对应的列表
     * @param  [type]  $f_catalog_id [description]
     * @param  [type]  $s_catalog_id [description]
     * @param  [type]  $lastid       [description]
     * @param  integer $rn           [description]
     * @return [type]                [description]
     */
    public static function getCourseRecommendList($f_catalog_id, $s_catalog_id, $lastid = NULL, $rn = 50, $is_pay) {
        $redis = Yii::$app->cache;

        if ($is_pay == 3) {
            $rediskey = "course_list_" . $f_catalog_id . "_" . $s_catalog_id . '_3';
        } else {
            $rediskey = "course_list_" . $f_catalog_id . "_" . $s_catalog_id . '_1';
        }

        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getCourseRecommendListData($f_catalog_id, $s_catalog_id, $is_pay);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['courseid'] . ',';
                $ret = $redis->rpush($rediskey, $value['courseid'], true);
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
     * 数据库获取列表
     * @param  [type] $f_catalog_id [description]
     * @param  [type] $s_catalog_id [description]
     * @return [type]               [description]
     */
    public static function getCourseRecommendListData($f_catalog_id, $s_catalog_id, $is_pay) {
        $query = self::find()->select('courseid')->where(['f_catalog_id' => $f_catalog_id])->andWhere(['status' => 2]);
        if (intval($s_catalog_id) > 0) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        //付费课程   #3免费 1收费
        if ($is_pay == 3) {
            $query->andWhere(['is_free' => 0]);
        } else if ($is_pay == 1) {
            $query->andWhere(['is_free' => 1]);
        }
        return $query->orderBy("courseid desc")->all();
    }

}
