<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectItemService;

/**
 * 删除 排序
 */
class EditemAction extends MBaseAction {

    public $resource_id = 'operation_course';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $itemid = $request->post('itemid');
        $value = $request->post('value');
        $subjectid = $request->post('subjectid');
        $type = $request->post('type');
        if (!$itemid || !is_numeric($itemid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = VideoSubjectItemService::findOne(['itemid' => $itemid]);
        if ($model) {
            //排序
            if ($type == 1) {
                $model->listorder = $value;
                $ret = $model->save();
                if ($ret) {
                    return $this->controller->outputMessage(['errno' => 0]);
                }
            } else if ($type == 2) {
                VideoSubjectItemService::deleteAll(['itemid' => $itemid]);
                $redis = Yii::$app->cache;
                $redis->delete('video_subject_' . $subjectid); //删除一招下面课程列表缓存
                 return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
