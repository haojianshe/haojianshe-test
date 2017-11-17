<?php

namespace api\service;

use Yii;
use common\models\myb\PosidHomeUser;

/**
 * 
 * @author ihziluoh
 * 
 * 出版社顶部广告位
 */
class PosidHomeUserService extends PosidHomeUser {

    public static function getPublishingTopAdv($uid) {
        $ret = self::find()->select("*")->where(['uid' => $uid])->andWhere(['status' => 1])->andWhere(['advert_type' => 1])->orderBy("listorder desc")->asArray()->all();
        if ($ret) {
            return $ret;
        } else {
            return [];
        }
    }

    /**
     * 返回画室的广告信息
     * @param type $uid
     * @return type
     */
    public static function getStudioPosid($uid) {
        $redis = Yii::$app->cache;
        $rediskeyList = "studio_posid_list_" . $uid;
        $mlist = $redis->get($rediskeyList);
        if (empty($mlist)) {
            //数据库获取
            $newData = [];
            #posidid,uid,img,url,listorder,status,advert_type,ctime
            $newData = self::find()->select("img,url,listorder")->where(['uid' => $uid])->andWhere(['status' => 1])->andWhere(['advert_type' => 2])->orderBy("listorder desc")->asArray()->all();
            $mlist = json_encode($newData);
            $redis->set($rediskeyList, $mlist);
            $redis->expire($rediskeyList, 3600 * 24 * 3);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

}
