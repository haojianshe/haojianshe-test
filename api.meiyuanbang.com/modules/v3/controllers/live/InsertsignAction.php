<?php

namespace api\modules\v3\controllers\live;

use Yii;
use mobile\components\MBaseAction;
use common\models\myb\LiveSign;
use mobile\service\LiveSignService;

#use mobile\service\CourseService;

class InsertsignAction extends MBaseAction {

    public function run() {
        $request = Yii::$app->request;
        //用户id
        $uid = $request->get('uid');
        //直播课id
        $liveid = $request->get('liveid');
        $liveSign = LiveSignService::getRedisLiveSign($liveid, $uid);
        if (empty($liveSign)) {
            $liveSign = new LiveSign();
            $liveSign->liveid = $liveid;
            $liveSign->uid = $uid;
            $liveSign->ctime = time();
            if ($liveSign->save()) {
                $redis = Yii::$app->cache;
                $rediskey = "live_sign_" . $liveid . "_" . $uid;
                $redis->set($rediskey, 1);
                $redis->expire($rediskey, 3600 * 24 * 3);
                $rediskeyLive = "live_sign_" . $liveid;
                $redis->delete($rediskeyLive);
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        } else {
            echo 0;
            exit;
        }
        //开启测试后动态调用用户uid
        # $uid = $this->_uid;
    }

}
