<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\LiveService;
use mis\service\LiveRecommendService;

/**
 * mis用户删除action
 */
class ReminsertAction extends MBaseAction {

    public $resource_id = 'operation_video';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        //检查参数是否非法
        $status = $request->post('status');
        $liveid = $request->post('liveid');

        if ($status == 1) {
            $model = new LiveRecommendService();
            $model->ctime = time();
            $model->liveid = $liveid;
            $model->sort_id = 0;
            $ret = $model->save();
        } else {
            $model = LiveRecommendService::deleteAll(['liverecid' => $liveid]);
        }
        //删除推荐的缓存
        $redis = Yii::$app->cache;
        $redis_key = 'live_recommend';
        $redis->delete($redis_key);
    }

}
