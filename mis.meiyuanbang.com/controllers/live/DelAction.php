<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\LiveService;
use mis\service\LiveRecommendService;

/**
 * mis用户删除action
 */
class DelAction extends MBaseAction {

    public $resource_id = 'operation_video';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $liveid = $request->post('liveid');

        if (!$liveid || !is_numeric($liveid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = LiveService::findOne(['liveid' => $liveid]);
        if ($model) {
            $model->status = 2;
            $ret = $model->save();
            LiveService::delCache($liveid, $model->teacheruid);
            if ($ret) {
                LiveRecommendService::deleteAll(['liveid' => $liveid]);
                $redis = \Yii::$app->cache;
                $redis_key = 'live_recommend';
                $redis->delete($redis_key);
                $redis->delete("studio_coures_list");
                $redis->delete("studio_live_list");
                $redis->delete('studio_studio_course' .$model->teacheruid);
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }

        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
