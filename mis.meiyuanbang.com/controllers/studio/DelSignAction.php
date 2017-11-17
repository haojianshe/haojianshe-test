<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioEnrollService;
use mis\service\StudioMenuService;

/**
 * 删除 画室 地址
 */
class DelSignAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $enrollid = $request->post('enrollid');
        $classtypeid = $request->post('classtypeid');
        $uid = $request->post('uid');
     
        if (!$enrollid || !is_numeric($enrollid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioEnrollService::findOne(['enrollid' => $enrollid]);
        if ($model) {
            StudioMenuService::delCache($classtypeid, $uid);
            $model->status = 2;
            //审核判断章节视频是否为空
            //获取章节
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

}
