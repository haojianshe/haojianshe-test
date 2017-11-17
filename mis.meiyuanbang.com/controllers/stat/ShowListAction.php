<?php

namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\CorrectService;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\CommentService;

/**
 * 违规统计
 * 
 */
class ShowListAction extends MBaseAction {

    public $resource_id = 'operation_stat';

    public function run() {

        $request = Yii::$app->request;
        //用于返回页面筛选时间
        $search_con['start_time'] = $request->get('start_time');
        $uid = $request->get('uid');
        if (empty($search_con['start_time'])) {
            $search_con['start_time'] = date("Y-m-d 00:00:00", strtotime("-1 day"));
        }
        $search_con['end_time'] = $request->get('end_time');
        if (empty($search_con['end_time'])) {
            $search_con['end_time'] = date('Y-m-d 00:00:00');
        }
        $start_time = strtotime($search_con['start_time']);
        $end_time = strtotime($search_con['end_time']);
        //得到所有批改老师列表
        $teachers = UserService::getAllCorrectTeacher($uid);

        foreach ($teachers as $key => $value) {
            //增加获取的对应响应时间数量及分数
            $teachers[$key] = array_merge($teachers[$key], CorrectService::getCorrectList($value['uid'], $start_time, $end_time));
        }


        return $this->controller->render('showlist', ["models" => $teachers, "is_search" => 1, "search" => $search_con, "total_info" => $total_info, "subcount" => $subcount]);
    }

}
