<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectItemService;

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
        $courseid = $request->post('courseid');
        $subjectid = $request->post('subjectid');
        $status = $request->post('status');
        if (!$subjectid || !is_numeric($subjectid)) {
            die('参数不正确');
        }
        //添加
        if ($subjectid) {
            #添加
            if ($status == 1) {
                $str = " insert  into myb_video_subject_item (subjectid,courseid,ctime) values ('$subjectid','$courseid','" . time() . "')";
                $commandSql = $connection->createCommand($str);
                $commandSql->execute();
            } else {
                #删除
                $str = " delete  from myb_video_subject_item where subjectid=$subjectid and courseid=$courseid";
                $commandSql = $connection->createCommand($str);
                $commandSql->execute();
            }
            $redis = Yii::$app->cache;
            $redis->delete('video_subject_' . $subjectid); //删除一招下面课程列表缓存
            return $this->controller->outputMessage(['errno' => 0]);
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
