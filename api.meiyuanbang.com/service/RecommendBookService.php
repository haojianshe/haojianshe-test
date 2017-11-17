<?php
namespace api\service;

use Yii;
use common\models\myb\RecommendBook;

/**
 * 
 * @author ihziluoh
 * 
 * 美院帮推荐图书
 */
class RecommendBookService extends RecommendBook {
    /**
     *得到美院帮推荐图书id
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getRecommendBooksList($f_catalog_id,$lastid=NULL,$rn=50){
        $redis = Yii::$app->cache;
        $rediskey="myb_recomend_books_".$f_catalog_id;
        //$redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getRecommendBooksDb($f_catalog_id);
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
     * 根据分类获取图书列表
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getRecommendBooksDb($f_catalog_id){
        $query=self::find()->select("bookid")->distinct()->where(['status'=>1]);
        if($f_catalog_id >0 ){
            $query->andWhere(['f_catalog_id'=>$f_catalog_id]);
        }
        $ret=$query->orderBy("recid desc")->asArray()->all();
        if($ret){
            return $ret;
        }else{
            return [];
        }
    }
}
