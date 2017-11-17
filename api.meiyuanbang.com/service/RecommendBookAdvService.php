<?php
namespace api\service;

use Yii;
use common\models\myb\RecommendBookAdv;

/**
 * 
 * @author ihziluoh
 * 
 * 图书广告推荐位
 */
class RecommendBookAdvService extends RecommendBookAdv {
    /**
     * 查找推荐图书
     * @param  [type] $adv_type [description]
     * @param  [type] $uid      [description]
     * @return [type]           [description]
     */
    public static function getRecommendAdvList($adv_type,$uid){
        //查找所有图书
        $ret=self::find()->select("bookid")->where(['uid'=>$uid])->andWhere(['adv_type'=>$adv_type])->andWhere(['status'=>1])->orderBy("listorder asc")->asArray()->all();
        if($ret){
            $return_data=[];
            //返回图书id
            foreach ($ret as $key => $value) {
               $return_data[]= $value['bookid'];
            }
            return $return_data;
        }else{
            return [];
        }
    }
}
