<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;

/**
 * 删除 画室广告
 */
class DeladvertAction extends MBaseAction {

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
        $posidid = $request->post('posidid');
        $uid = $request->post('uid');
     
        if (!$posidid || !is_numeric($posidid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = PosidHomeUserService::findOne(['posidid' => $posidid]);
        if ($model) {
          PosidHomeUserService::delCache($uid);
            $model->status = 2;
            //审核判断章节视频是否为空
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

}
