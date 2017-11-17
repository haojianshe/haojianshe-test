<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\RecommendBook;
use mis\service\LectureTagService;

/**
 * 修改添加推荐
 */
class AddadvbookAction extends MBaseAction {

    public $resource_id = 'operation_lecture';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $chk_value = $request->post('chkval');
        $array = array_unique($chk_value);
        if ($chk_value) {
            $_SESSION['chkval'] = implode(',', $array);
        } else {
            $_SESSION['chkval'] = '';
        }
    }

}
