<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;

/**
 * 删除活动方法
 */
class DelAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $activityid = $request->post('activityid');
        $status = $request->post('status');
        if (!$activityid || !is_numeric($activityid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = LkActivityService::findOne(['lkid' => $activityid]);
        if ($model) {
            if ($status > 0) {
                $model->rank_status = $status;
                $redis = Yii::$app->cache;
                $rediskey = "lk_" . $activityid;
                $redis->delete($rediskey);
                #清空联考列表缓存
            } else {
                $model->status = 2;
            }

            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
