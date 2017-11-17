<?php

namespace api\service;

use Yii;
use common\models\myb\ActivityArticle;
use common\models\myb\StudioEnroll;

/**
 * 
 * 班型报名方式
 */
class StudioEnrollService extends StudioEnroll {

    /**
     * 获得报名方式班型信息
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getEnorllInfo($enroll) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_enroll_detail_' . $enroll; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())->select('b.menuid,b.uid')
                    ->from('myb_studio_enroll as b')
                    #->innerJoin('myb_studio as a', 'a.uid=b.uid')
                    ->where(["b.uid" => $uid])
                    ->andWhere(["b.menu_type" => 1])//审核通过
                    ->andWhere(["b.enrollid" => $enroll])//审核通过
                    #->andWhere(["a.status" => 3])//审核通过
                    ->orderBy('b.listorder desc')
                    ->all();
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 调用接口Get方法
     * @param [type] $url [description]
     */
    static function HttpGetOrder($url,$data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        print_r($output);
        exit;
        curl_close($ch);
        return json_decode($output, true);
    }

}
