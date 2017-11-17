<?php

namespace api\service;

use Yii;
use common\models\myb\ActivityArticle;
use common\models\myb\lkMaterialRelation;
use common\models\myb\News;
use common\models\myb\NewsData;
use api\service\ResourceService;
use api\service\FavoriteService;

/**
 * 
 * @author ihziluoh
 * 
 * 活动文章
 */
class ActivityArticleService extends ActivityArticle {

    /**
     * 活动文章信息（可多个）
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getAllArticleInfo($newsid_arr) {
        $ret_arr = [];
        foreach ($newsid_arr as $key => $value) {
            $info = self::getArticleDetail($value);
            $ret_arr[] = $info;
        }
        return $ret_arr;
    }

    /**
     * 获取活动文章详情
     */
    public static function getArticleDetail($newsid, $uid = -1) {
        $rediskey = "activity_article_" . $newsid;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::getArticleDetailDb($newsid);
            if($detail){
                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }           
        }
        //处理图片
        if ($detail) {
            $img = [];
            if ($detail['cover_type'] > 1 && $detail['cover_type'] < 5) {
                $thumbs = explode(",", $detail['thumb']);
                foreach ($thumbs as $key => $value) {
                    $img[] = ResourceService::getResourceDetail($value);
                }
                $detail['imgs'] = $img;
            } else{
                $detail['imgs'] = array();
            }
            if ($uid > 0) {
                $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['newsid'], 3);
            } else {
                $detail['fav'] = 0;
            }
            $detail['share_url'] = Yii::$app->params['sharehost'] . "/activity/article_detail?newsid=" . $newsid;
            $detail['url'] = Yii::$app->params['sharehost'] . "/activity/article_detail?newsid=" . $newsid;
        }
        
        return $detail;
    }

    /**
     * 数据库获取文章详情
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getArticleDetailDb($newsid) {
        $ret = self::find()->where(['newsid' => $newsid])->asArray()->one();
        $news = News::find()->where(['newsid' => $newsid])->asArray()->one();
        $newsdata = NewsData::find()->select("hits,cmtcount,supportcount,copyfrom,reserve1,reserve2,reserve3")->where(["newsid" => $newsid])->asArray()->one();
        if (empty($ret) || empty($news) || empty($newsdata)) {
            return [];
        }

        $return_arr = array_merge($ret, $news, $newsdata);


        return $return_arr;
    }

}
