<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\LiveRecommendService;

/**
 * mis用户删除action
 */
class EditremAction extends MBaseAction {

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
        $value = $request->post('value');

        if (!$liveid || !is_numeric($liveid)) {
            die('参数不正确');
        }

        //根据id取出数据
        $model = LiveRecommendService::findOne(['liverecid' => $liveid]);
        if ($model) {
            $model->sort_id = $value;
            $model->save();
            //删除推荐的缓存
            $redis = Yii::$app->cache;
            $redis_key = 'live_recommend';
            $redis->delete($redis_key);
            return $this->controller->outputMessage(['errno' => 0]);
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '修改失败']);
    }

}
