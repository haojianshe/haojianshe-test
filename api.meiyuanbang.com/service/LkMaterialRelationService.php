<?php

namespace api\service;

use Yii;
use common\models\myb\LkMaterialRelation;
use api\service\ActivityArticleService;

/**
 * 
 * @author ihziluoh
 * 
 * 联考文章关系表
 */
class LkMaterialRelationService extends LkMaterialRelation {

    /**
     * 文章列表缓存获取
     * @param  [type]
     * @param  [type] 类型 1/2/3 状元分享会/名师大讲堂/联考攻略
     * @param  [type]
     * @param  integer
     * @return [type]
     */
    public static function getArticleList($lkid, $type, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        $rediskey = "article_list_" . $lkid . '_' . $type;
         //$redis->delete($rediskey);
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getArticleListDb($lkid, $type, $lastid);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['newsid'] . ',';
                $ret = $redis->rpush($rediskey, $value['newsid'], true);
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
     * 联考活动文章问答信息（可多个）
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getAllLkArticleInfo($lkid, $newsid_arr) {
        $ret_arr = [];
        foreach ($newsid_arr as $key => $value) {
            //类型 1/2/3 状元分享会/名师大讲堂/联考攻略
            $lknews = self::getLkNewsDetail($lkid, $value);
            switch ($lknews['zp_type']) {
                case '1':
                    $info = ActivityQaService::getQaDetail($value);
                    break;
                case '2':
                case '3':
                    $info = ActivityArticleService::getArticleDetail($value);
                    break;
            }
            if ($info && $lknews) {
                $ret_arr[] = array_merge($info, $lknews);
            }
        }
        return $ret_arr;
    }

    /**
     * 获取联考文章信息
     */
    public static function getLkNewsDetail($lkid, $newsid) {
        $rediskey = "lk_news_" . $lkid . "_" . $newsid;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $ret = self::findOne(['lkid' => $lkid, "newsid" => $newsid]);
            if ($ret) {
                $data = $ret->attributes;
            }
            $redis->hmset($rediskey, $data);
            $redis->expire($rediskey, 3600 * 24 * 3);
            return $data;
        } else {
            return $detail;
        }
    }

    /**
     * @param  数据库获取文章列表
     * @param  [type]
     * @return [type]
     */
    public static function getArticleListDb($lkid, $type, $lastid) {
        $query = parent::find()->select('newsid')->where(['lkid' => $lkid])->andWhere(['status' => 1]);
        if ($type != "all") {
            //$query->andWhere(['zdtime'=>0]);
            $query->andWhere(['zp_type' => $type]);
        }
        $query->orderBy("zdtime desc,ctime desc");
        return $query->all();
    }

    /*
     * 获取优秀试卷
     */

    public static function getPicList($type, $offset) {
        $Page_size = 10;
        if ($offset == 1) {
            $offset = 0;
        } elseif ($offset > 1) {
            $offset = ($offset - 1) * $Page_size;
        }
        $sql = "select paper_url from myb_cf_paper_img where tiid>0 and  pic_type='$type' order by score desc,tiid desc limit $offset,$Page_size";
        $connection = Yii::$app->db; //连接
        $picdata = $connection->createCommand($sql);
        return $picdata->queryAll();
    }

}
