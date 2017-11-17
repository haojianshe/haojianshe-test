<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioAddressService;
use mis\service\PosidHomeUserService;

/**
 * 删除 画室 地址
 */
class DelAddressAction extends MBaseAction {

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
     
        if (!$addrid || !is_numeric($addrid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioAddressService::findOne(['addrid' => $addrid]);
        if ($model) {
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
