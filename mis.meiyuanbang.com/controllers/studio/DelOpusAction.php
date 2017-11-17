<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioOpusService;

#use mis\service\PosidHomeUserService;

/**
 * 删除 画室 地址
 */
class DelOpusAction extends MBaseAction {

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
        $addrid = $request->post('addrid');
        $uid = $request->post('uid');
        if (!$addrid || !is_numeric($addrid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioOpusService::findOne(['studioopusid' => $addrid]);
        if ($model) {
            $redis = \Yii::$app->cache;
            $redis_key = "myb_studio_opus_" . $uid;
            $redis_key_opusid = "studio_opus_" . $addrid;
            $redis->delete($redis_key);
            $redis->delete($redis_key_opusid);
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
