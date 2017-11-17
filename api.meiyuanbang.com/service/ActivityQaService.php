<?php

namespace api\service;

use Yii;
use common\models\myb\ActivityQa;
use common\models\myb\News;
use common\models\myb\NewsData;
use api\service\FavoriteService;

/**
 * 
 * @author ihziluoh
 * 
 * 活动问答
 */
class ActivityQaService extends ActivityQa {

    /**
     * 问答详情（可多个）
     * @param  [type]
     * @return [type]
     */
    public static function getAllQaInfo($newsid_arr) {
        $ret_arr = [];
        foreach ($newsid_arr as $key => $value) {
            $info = self::getQaDetail($value);
            if ($info) {
                $ret_arr[] = $info;
            }
        }
        return $ret_arr;
    }

    /**
     * 获取问答详情详情
     */
    public static function getQaDetail($newsid, $uid = -1) {
        $rediskey = "activity_qa_" . $newsid;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::getQaDetailDb($newsid);
            if ($detail) {
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
                $detail['fav'] = FavoriteService::getFavStatusByUidTid($uid, $detail['newsid'], 4);
            } else {
                $detail['fav'] = 0;
            }
            $detail['share_url'] = Yii::$app->params['sharehost'] . "/activity/qa_detail?newsid=" . $newsid;
            $detail['url'] = Yii::$app->params['sharehost'] . "/activity/qa_detail?newsid=" . $newsid;
        }
        return $detail;
    }

    /**
     * 数据库获取问答详情
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getQaDetailDb($newsid) {
        $ret = self::find()->where(['newsid' => $newsid])->asArray()->one();
        $news = News::find()->where(['newsid' => $newsid])->asArray()->one();
        $newsdata = NewsData::find()->select("hits,cmtcount,supportcount,copyfrom,reserve1,reserve2,reserve3")->where(["newsid" => $newsid])->asArray()->one();
        if (empty($ret) || empty($news) || empty($newsdata)) {
            return [];
        }
        $return_arr = array_merge($ret, $news, $newsdata);
        return $return_arr;
    }
    /**
     * 获取所有问答分页
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getAllQaList($lastid=NULL,$rn=50){
        $redis = Yii::$app->cache;
        $rediskey="all_qa_list";
       // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::find()->alias("qa")->select("qa.newsid")->innerJoin('myb_news as mn', 'mn.newsid=qa.newsid')->where(['mn.status'=>0])->orderBy("newsid desc")->all();
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['newsid'].',';
                $ret = $redis->rpush($rediskey, $value['newsid'],true);
            }
            $redis->expire($rediskey,3600*24*3);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        //分页数据获取
        if(empty($lastid)){
            $idx=0;
            $ids_data=$redis->lrange($rediskey,0, $rn-1);
        }else{
            $idx = array_search($lastid, $list_arr);
            $ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
        }
        return $ids_data;
    }

}
