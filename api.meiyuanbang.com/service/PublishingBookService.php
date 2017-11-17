<?php

namespace api\service;

use Yii;
use common\models\myb\PublishingBook;
use common\models\myb\News;
use common\models\myb\NewsData;
use api\service\ResourceService;
use api\service\FavoriteService;
use common\service\dict\BookDictDataService;
use common\service\CommonFuncService;
/**
 * 
 * @author ihziluoh
 * 
 * 出版社书籍
 */
class PublishingBookService extends PublishingBook {
    /**
     * 获取详情
     */
    public static function getPublishingBookInfo($bookid){
        $rediskey="publishing_book_".$bookid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail=$redis->hgetall($rediskey);
        if (empty($detail)) {
           $detail=self::getPublishingBookInfoDb($bookid);
           if($detail){
                $redis->hmset($rediskey,$detail);
                $redis->expire($rediskey,3600*24*3);
           }
        }
        //处理图片
        if ($detail) {
            //$img = [];
                /*$thumbs = explode(",", $detail['thumb']);
                foreach ($thumbs as $key => $value) {
                    $img[] = ResourceService::getResourceDetail($value);
                }*/
            $detail['img'] = ResourceService::getResourceDetail($detail['thumb'])['img'];
            $detail['img']->l = (object)CommonFuncService::getPicByType((array)($detail['img']->n),"l");
            $detail['url']=Yii::$app->params['sharehost']."/publishing/book_detail?bookid=".$detail['bookid'];
            //获取分类
            $detail['f_catalog']=BookDictDataService::getBookMainTypeById($detail['f_catalog_id']);
            $detail['s_catalog']=BookDictDataService::getBookSubTypeById($detail['f_catalog_id'],$detail['s_catalog_id']);
        }
        
        return $detail;
    }
    
    /**
     * 数据库获取图书详情
     * @param  [type] $bookid [description]
     * @return [type]         [description]
     */
    public static function getPublishingBookInfoDb($bookid) {
        $ret = self::find()->where(['bookid' => $bookid])->asArray()->one();
        if(empty($ret)){
            return [];
        }
        $newsid=$ret['newsid'];
        $news = News::find()->where(['newsid' => $newsid])->asArray()->one();
        $newsdata = NewsData::find()->select("hits,cmtcount,supportcount,copyfrom,reserve1,reserve2,reserve3")->where(["newsid" => $newsid])->asArray()->one();
        if (empty($news) || empty($newsdata)) {
            return [];
        }
        $return_arr = array_merge($news,$newsdata,$ret);
        return $return_arr;
    }

    /**
     * 根据书籍id数组获取书籍信息
     * @param  [type] $bookids [description]
     * @return [type]          [description]
     */
    public static function getPublishingBooksInfo($bookids){
        $ret_arr=[];
        foreach ($bookids as $key => $value) {
           $ret_arr[]=self::getPublishingBookInfo($value);
        }
        return $ret_arr;
    }
    /**
     * 出版社图书列表缓存
     * @param  [type]  $uid    [description]
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getPublishingBookList($uid,$lastid=NULL,$rn=50){
        $redis = Yii::$app->cache;
        $rediskey="publishing_books_".$uid;
       // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getPublishingBookListDb($uid);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['bookid'].',';
                $ret = $redis->rpush($rediskey, $value['bookid'],true);
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
    /**
     * 数据库取得对应出版社图书列表
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getPublishingBookListDb($uid){
        $ret=self::find()->select("bookid")->where(['status'=>1])->andWhere(['uid'=>$uid])->orderBy("bookid desc")->asArray()->all();
        if($ret){
            return $ret;
        }else{
            return [];
        }
    }

    /**
     * 得到图书数
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getBookCountByUid($uid){

        $rediskey="publishing_book_count_".$uid;
        $redis = Yii::$app->cache;

        //$redis->delete($rediskey);
        $count=$redis->get($rediskey);

        if (empty($count)) {
            $count=self::find()->where(['uid'=>$uid])->count();
            $redis->set($rediskey,$count);
            $redis->expire($rediskey,3600*24*3);
        }
        return $count;
    }

     /**
     * 根据一二级分类随机获取指定数量图书
     * @param  [type] $course_search_catalog [description]
     * @param  [type] $limit                 [description]
     * @return [type]                        [description]
     */
    public static function getBooksByCatalogRand($book_search_catalog){
        $bookids=[];
        if($book_search_catalog){
            foreach ($book_search_catalog as $key => $value) {
                $sbook=self::find()->select('bookid')->where(['status'=>1])->andWhere(['f_catalog_id'=>$value['f_catalog_id']])->andWhere(['s_catalog_id'=>$value['s_catalog_id']])->limit($value['limit'])->orderBy("rand()")->all();
                foreach ($sbook as $key1 => $value1) {
                    $bookids[]=$value1['bookid'];
                }
            }
        }
       
        return self::getPublishingBooksInfo($bookids);
    }
}
