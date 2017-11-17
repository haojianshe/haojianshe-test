<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\RecommendBookService;
use common\models\myb\RecommendBookAdv;

/**
 * 删除图书方法
 */
class DelbookAction extends MBaseAction {

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
        $recid = $request->post('recid');
        $status = $request->post('status');
        $bookid = $request->post('bookid');
        if (!$recid || !is_numeric($recid)) {
            die('参数不正确');
        }

        //根据id取出数据
        $model = RecommendBookService::deleteAll(['recid' => $recid]);
        if ($model) {
            $meiyuanbangModel = RecommendBookService::find()->where(['bookid' => $bookid])->Asarray()->all();
            if (!count($meiyuanbangModel)) {
                RecommendBookAdv::deleteAll(['bookid' => $bookid, 'uid' => -1]);
            }
            return $this->controller->outputMessage(['errno' => 0]);
        } else
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
