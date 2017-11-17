<?php

namespace api\service;

use Yii;
use common\redis\Cache;
use common\models\myb\Lecture;
use api\service\LectureTagService;
use api\service\LectureTagNewsService;
use api\service\FavoriteService;

/**
 * 
 * @author Administrator
 *
 */
class LectureService extends Lecture {

    /**
     * 获取能力模型页推荐的精讲信息
     * @param unknown $fcatalogid
     * @param unknown $scatalogid
     * @return unknown
     */
    static function getRecommend($fcatalogid, $scatalogid) {
        $redis = Yii::$app->cache;
        $rediskey = "lecture_recommend_" . $fcatalogid . '_' . $scatalogid;

        //从缓存获取
        $ret = $redis->getValue($rediskey);
        if ($ret) {
            return json_decode($ret, true);
        }
        //从数据库获取id
        $query = (new \yii\db\Query())
                ->select(['newsid'])
                ->from(parent::tableName())
                ->where(['status' => 0])
                ->andWhere(['publishtime' => 0]);

        if ($fcatalogid > 0) {
            $query = $query->andWhere(['lecture_level1' => $fcatalogid])
                    ->andWhere(['lecture_level2' => $scatalogid]);
        }
        $newsid = $query->orderBy('newsid DESC')
                ->limit(1)
                ->one();
        if (!$newsid) {
            return null;
        }
        $newsid = $newsid['newsid'];
        //根据newsid获取精讲信息
        $ret = NewsService::getLectureInfo($newsid);
        //缓存5分钟
        $redis->setValue($rediskey, json_encode($ret), 300);
        return $ret;
    }

    /**
     * 根据一二级分类随机获取指定数量精讲
     * @param  [type] $course_search_catalog [description]
     * @param  [type] $limit                 [description]
     * @return [type]                        [description]
     */
    public static function getLectureByCatalogRand($news_search_catalog, $limit) {
        $query = self::find()->select('newsid');
        $where_catalog = '';
        if ($news_search_catalog) {
            foreach ($news_search_catalog as $key => $value) {
                if ($where_catalog != '') {
                    $where_catalog.= 'or';
                }
                $where_catalog.=" (lecture_level1=" . $value['f_catalog_id'] . ")";
            }
            if ($where_catalog) {
                $where_catalog = '(' . $where_catalog . ')';
            }
            //$query->andWhere($where_catalog);
        }
        //3.1.1add by ljq,取最近30条记录的随机，否则可能取到太久远的记录
        //暂时兼容andriod bug，不返回左图 3图文章 不返回专题，客户端升级后需要修改此段代码
        $rankwhere = "newsid in (select t.newsid from (select newsid from " . parent::tableName() . " where status=0 and (thumbtype=1) and newstype=1 ";
        if ($where_catalog) {
            $rankwhere .= " and $where_catalog ";
        }
        $rankwhere .= " order by publishtime desc limit 30) as t)";
        $query->andWhere($rankwhere);
        $newsids_arr = $query->limit($limit)->orderBy("rand()")->all();
        $ret_data = [];
        foreach ($newsids_arr as $key => $value) {
            $ret_data[] = self::getLectureInfo($value['newsid']);
        }
        return $ret_data;
    }

    /**
     * 缓存分页获取精讲文章 专题列表
     * @param  [type]  $lecture_level1 [description]
     * @param  [type]  $lastid         [description]
     * @param  integer $rn             [description]
     * @return [type]                  [description]
     */
    public static function getAllLectureListRedis($lecture_level1, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "all_lecture_list_" . $lecture_level1;
        //$redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getAllLectureList($lecture_level1);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['newsid'] . ',';
                $ret = $redis->rpush($rediskey, $value['newsid'], true);
            }
            //精讲列表缓存300秒
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
     * 得到所有精讲专题及文章id
     * @param  [type] $lecture_level1 [description]
     * @return [type]                 [description]
     */
    public static function getAllLectureList($lecture_level1) {
        $query = self::find()->select("newsid")->where(['status' => 0])->andWhere(['is_in_list' => 1])->andWhere(['<', 'publishtime', time()]);
        if ($lecture_level1 > 0) {
            $query->andWhere(["lecture_level1" => $lecture_level1]);
        } else {
            $query->andWhere(['stick_date' => 0]);
        }
        return $query->orderBy("publishtime desc")->all();
    }

    /**
     * 精讲置顶列表
     * @return [type] [description]
     */
    public static function getTopLectureListRedis() {
        $rediskey = "top_lecture";
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->get($rediskey);
        if (empty($detail)) {
            $detail = self::getTopLectureList();
            if ($detail) {
                $detail = json_encode($detail);
                $redis->set($rediskey, $detail);
                $redis->expire($rediskey, 300);
            } else {
                return $detail;
            }
        }
        return json_decode($detail);
    }

    /**
     * 获取置顶精讲及精讲专题
     * @return [type] [description]
     */
    public static function getTopLectureList() {
        $newsid_arr = self::find()->select('newsid')->where(['status' => 0])->andWhere(['<', 'publishtime', time()])->andWhere(['is_in_list' => 1])->andWhere(['>', 'stick_date', 0])->orderBy('stick_date desc')->all();
        $newsids = [];
        if (count($newsid_arr) > 0) {
            foreach ($newsid_arr as $key => $value) {
                $newsids[] = $value['newsid'];
            }
        }
        return $newsids;
    }

    /**
     * 获取列表精讲专题信息
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getLectureListInfo($newsids, $uid = -1) {
        $list = [];
        foreach ($newsids as $key => $value) {
            $list[] = self::getLectureInfo($value, $uid);
        }
        return $list;
    }

    /**
     * 获取详情
     */
    public static function getLectureInfo($newsid, $uid = -1) {
        $rediskey = "lecture_detail_new_" . $newsid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::find()->select(self::tableName() . ".*," . 'myb_news.*,myb_news_data.hits,myb_news_data.cmtcount,myb_news_data.supportcount')->where([self::tableName() . '.newsid' => $newsid])->innerJoin("myb_news", "myb_news.newsid=" . self::tableName() . '.newsid')->innerJoin("myb_news_data", "myb_news_data.newsid=" . self::tableName() . '.newsid')->asArray()->one();
            if ($detail) {
                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }
        }
        $rids = explode(',', $detail['thumb']);
        foreach ($rids as $rid) {
            $resourcemodel = ResourceService::findOne(['rid' => $rid]);
            //容错，rid有效，并且img有效
            if (!$resourcemodel) {
                continue;
            }
            if (empty($resourcemodel['img'])) {
                continue;
            }
            $imgs_arr = $resourcemodel->attributes;
            //兼容老版本
            $imgs_arr['url'] = $imgs_arr['img'];
            $imgs[] = $imgs_arr;
        }

        $detail['img'] = $imgs;

        $detail['fav'] = 0;
        //是否是精讲或者专题 1=>精讲,2=>专题
        switch (intval($detail['newstype'])) {
            case 1:
                $detail['url'] = Yii::$app->params['sharehost'] . '/lecture?id=' . $newsid;
                $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['newsid'], 2);
                $favinfo = FavoriteService::getFavInfoByContent($detail['newsid'], 2);
                $detail = array_merge($detail, $favinfo);
                break;
            case 2:
                $detail['url'] = Yii::$app->params['sharehost'] . '/lecture/subject?newsid=' . $newsid;
                $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['newsid'], 8);
                $favinfo = FavoriteService::getFavInfoByContent($detail['newsid'], 8);
                $detail = array_merge($detail, $favinfo);
                break;
        }
        return $detail;
    }

    /**
     * 数据库获取精讲专题详情
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getSubjectDetail($newsid) {
        $ret = [];
        $lecture_tags = LectureTagService::getLectureTagByNewsid($newsid);
        foreach ($lecture_tags as $key => $value) {
            $lecture_tags[$key]['lecture_list'] = LectureTagNewsService::getLectureTagNewsByTagid($value['lecture_tagid']);
        }
        return $lecture_tags;
    }

    /**
     * 获取精讲专题详情
     */
    public static function getSubjectDetailRedis($newsid) {
        $rediskey = "lecture_subject_detail_" . $newsid;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->get($rediskey);
        if (empty($detail)) {
            $detail = self::getSubjectDetail($newsid);
            if ($detail) {
                $detail = json_encode($detail);
                $redis->set($rediskey, $detail);
                $redis->expire($rediskey, 3600);
            } else {
                return $detail;
            }
        }
        return json_decode($detail);
    }

    /**
     * 搜索文章及文章专题列表
     * @param  [type] $keyword [description]
     * @return [type]          [description]
     */
    public static function getSearchLectureList($keyword) {
        $newids_arr = self::find()->select("a.newsid")->alias("a")->innerJoin("myb_news as b", "a.newsid=b.newsid")->where(['a.status' => 0])->andWhere(['like', "b.title", $keyword])->asArray()->limit(100)->all();
        $newsids = [];
        foreach ($newids_arr as $key => $value) {
            $newsids[] = $value['newsid'];
        }
        return $newsids;
    }

    /**
     * 增加浏览量
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function update_hits($newsid) {
        $connection = Yii::$app->db;
        $command = $connection->createCommand('update myb_news_data set hits=hits+1 where newsid=' . $newsid);
        $res = $command->execute();
        $redis = Yii::$app->cache;
        $redis_key = 'lecture_detail_new_' . $newsid;
        $redis->hincrby($redis_key, 'hits', +1);
    }

    /**
     * @desc 重新排列数组顺序
     * @param type $data  数据源
     * @param type $array 身份和位置信息
     */
    static public function getNewData($data, $array = []) {
        if ($data['top_lecture']) {
            //循环去掉置顶 符合条件的数据
            foreach ($data['top_lecture'] as $k => &$v) {
                if ($v['proviceids'] && $v['professionids']) {
                    if ($v['newstype'] == 2) {
                        if (!in_array($array['provinceid'], explode(',', $v['proviceids'])) || !in_array($array['professionid'], explode(',', $v['professionids']))) {
                            unset($data['top_lecture'][$k]);
                            $data['top_lecture'] = array_merge($data['top_lecture']);
                        }
                    }
                }
            }
        }
        if ($data['list']) {
            //循环去掉列表中 符合条件的专题数据
            foreach ($data['list'] as $kk => &$vv) {
                if ($vv['proviceids'] && $vv['professionids']) {
                    if ($vv['newstype'] == 2) {
                        if (!in_array($array['provinceid'], explode(',', $vv['proviceids'])) || !in_array($array['professionid'], explode(',', $vv['professionids']))) {
                            unset($data['list'][$kk]);
                            $data['list'] = array_merge($data['list']);
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @desc 获取数据
     * @param type $lecture_level1
     * @param type $lastid
     * @param type $rn
     * @return type
     */
    static public function getLectureData($lecture_level1, $lastid, $rn) {
        //获取置顶精讲及精讲专题 
        $top_newsids = LectureService::getTopLectureListRedis();
        //只有推荐的第一页返回置顶
        $data['top_lecture'] = [];
        if ($lastid == 0 && $lecture_level1 == 0) {
            $data['top_lecture'] = LectureService::getLectureListInfo($top_newsids);
        }
        //分页获取精讲文章，精讲专题列表
        $newsids = LectureService::getAllLectureListRedis($lecture_level1, $lastid, $rn);
        $data['list'] = LectureService::getLectureListInfo($newsids);
        return $data;
    }

}
