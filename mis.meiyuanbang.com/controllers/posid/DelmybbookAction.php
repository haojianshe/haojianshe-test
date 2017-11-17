<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\RecommendBookAdv;
use mis\service\PublishingBookService;
/**
 * 删除能力模型推荐
 */
class DelmybbookAction extends MBaseAction {

    public $resource_id = 'operation_posid';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $typeid = $request->post('typeid');
        $value = $request->post('value');
        $status = $request->post('status');

        if (!$value || !is_numeric($value)) {
            die('参数不正确');
        }
        //添加
        if ($status == 1) {
            $model = new RecommendBookAdv();
        } else {
            //取消
            $model = RecommendBookAdv::findOne(['adv_type' => $typeid, 'bookid' => $value, 'uid' => -1]);
            $advid = $model->advid;
        }
        if ($model) {
            if ($status == 2) {
                // $model->status = 2;
                $model = RecommendBookAdv::deleteAll(['adv_type' => $typeid, 'bookid' => $value, 'uid' => -1]);
            } else {
                $model->status = 1;
                $model->adv_type = $typeid;
                $model->uid = -1;
                $model->bookid = $value;
                $model->listorder = 0;
                $model->ctime = time();
                $ret = $model->save();
                $advid = $model->attributes['advid'];
            }
             #去掉图书列表和单个图书编辑的缓存
            PublishingBookService::setCaheBook('', $advid,3);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
