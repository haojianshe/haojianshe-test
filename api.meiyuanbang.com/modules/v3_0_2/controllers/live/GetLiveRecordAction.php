<?php

namespace api\modules\v3_0_2\controllers\live;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;

/**
 * 获取直播用户观看记录
 */
class GetLiveRecordAction extends ApiBaseAction {

    public function run() {
        $liveid = $this->requestParam('liveid', true); //直播id 
        $uid = $this->_uid; // $this->_uid;$this->requestParam('uid');
        $subjecttype = $this->requestParam('subjecttype', true); // 直播列表
        $record = $this->requestParam('record'); // 直播列表
        if ($record == 1) {
            //首次访问时，浏览量加一
            $hits = LiveService::addHits($liveid, 1);
        } else {
            if (!isset($record)) {
                $hits = LiveService::addHits($liveid, 1);
            } else {
                //如果不是第一次访问，从缓存中拿浏览量
                $key = 'live_detail_' . $liveid;
                $redis = Yii::$app->cache;
                $liveData = $redis->hgetall($key);
                if (empty($liveData)) {
                    $hits = LiveService::addHits($liveid, 1);
                } else {
                    $hits = $liveData['hits'];// + $liveData['hits_basic'];
                }
            }
        }
        if ($subjecttype == 10) {
            $ret = LiveService::getLiveSignNumber($liveid, $uid, 1);
            if (empty($ret['data'])) {
                $ret['data'] = array();
            }
            $ret['hits'] = $hits;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
        } else {
            $ret = [];
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
        }
    }

}
