<?php

namespace mis\service;

use Yii;
use common\models\myb\UserPush;

/**
 * 用户详情相关逻辑
 */
class UserPushService extends UserPush {

    /**
     * 根据uid获取用户最后一个设备的token
     */
    public static function getByUid($uid) {
        //从数据库中获取
        $ret = (new \yii\db\Query())
                ->select('xg_device_token')
                ->from(parent::tableName())
                ->where(['uid' => $uid])
                ->orderBy('id DESC')
                ->all();
        $ret_token = [];
        if ($ret) {
            foreach ($ret as $key => $value) {
                $ret_token[] = $value['xg_device_token'];
            }
        }
        return $ret_token;
    }

}
