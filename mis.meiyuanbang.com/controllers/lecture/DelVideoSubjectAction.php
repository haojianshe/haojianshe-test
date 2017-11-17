<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\LectureTagNews;
use common\models\myb\News;

/**
 * 删除一招下面的课程
 */
class DelVideoSubjectAction extends MBaseAction {

    public $resource_id = 'operation_course';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        $connection = Yii::$app->db;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $lecture_tagid = $request->post('lecture_tagid');
        $newsid = $request->post('newsid');
        $status = $request->post('status');

        if (!$lecture_tagid || !is_numeric($newsid)) {
            die('参数不正确');
        }
        //添加
        if ($newsid) {
            if ($status == 1) { #添加
                $news = News::findOne(['newsid' => $newsid]);
                $models = new LectureTagNews(); //LectureTagNews::findOne(['lecture_tagid' => $lecture_tagid, 'newsid' => $newsid]);
                $models->title = $news->title;
                $models->lecture_tagid = $lecture_tagid;
                $models->newsid = $newsid;
                $models->ctime = time();
                $models->save();
            } else {
                #删除
                $models = LectureTagNews::deleteAll(['lecture_tagid' => $lecture_tagid, 'newsid' => $newsid]);
            }
             return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
        return $this->controller->outputMessage(['errno' => 0]);
       
    }

}
