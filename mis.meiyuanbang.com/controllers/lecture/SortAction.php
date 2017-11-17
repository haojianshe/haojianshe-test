<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\LectureTagNews;
use mis\service\LectureTagService;

/**
 * mis用户删除action
 */
class SortAction extends MBaseAction {

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
        $tag_news_id = $request->post('tag_news_id');
        $type = $request->post('type');
        $value = $request->post('value');
        if (!$tag_news_id || !is_numeric($tag_news_id)) {
            die('参数不正确');
        }
         unset($_SESSION['chkval']);
        //根据id取出数据
        $model = LectureTagNews::findOne(['tag_news_id' => $tag_news_id]);
        if ($model) {
            $newsModel = LectureTagService::findOne(['lecture_tagid' => $model->lecture_tagid]);
            #echo "lecture_detail_new_".$newsModel->newsid;
            #exit;
            $redis = Yii::$app->cache;
            $redis->delete("lecture_subject_detail_" . $newsModel->newsid);
            if ($type == 2) {
                #排序
                $model->listorder = $value;
            } else {
                #删除
                $model->status = $value;
            }
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
