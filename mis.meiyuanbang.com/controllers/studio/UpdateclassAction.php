<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioClasstypeService;
use mis\service\UserService;
use mis\service\StudioMenuService;
use mis\service\PosidHomeUserService;

/**
 * 删除 审核 课程
 */
class UpdateclassAction extends MBaseAction {

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
        $classtypeid = $request->post('classtypeid');
        $uid = $request->post('uid');
        $type = $request->post('type');

        if (!$classtypeid || !is_numeric($classtypeid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioClasstypeService::findOne(['classtypeid' => $classtypeid]);
        if ($model) {
            StudioClasstypeService::delCache($uid);
            if ($type == 2) {
                $model->status = 1;
            } else {
                $model->status = 3;
            }
            //审核判断章节视频是否为空
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

}
