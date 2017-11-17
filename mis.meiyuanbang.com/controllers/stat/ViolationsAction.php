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
class ViolationsAction extends MBaseAction {

    public $resource_id = 'operation_stat';

    public function run() {

        $request = Yii::$app->request;
        //用于返回页面筛选时间
        $search_con['start_time'] = $request->get('start_time');
        if (empty($search_con['start_time'])) {
            $search_con['start_time'] = date("Y-m-d 00:00:00", strtotime("-1 day"));
        }
        $search_con['end_time'] = $request->get('end_time');
        if (empty($search_con['end_time'])) {
            $search_con['end_time'] = date('Y-m-d 00:00:00');
        }
        //判断是否通过时间搜索  要是不通过时间搜索则显示空页
        if (!$request->get('is_search')) {
            $search_con['is_search'] = 0;
            return $this->controller->render('violations', ["is_search" => 1, "search" => $search_con]);
        }
        $search_con['is_search'] = 1;
        $start_time = strtotime($search_con['start_time']);
        $end_time = strtotime($search_con['end_time']);
        //得到所有批改老师列表
        $teachers = UserService::getAllCorrectTeacher();
        foreach ($teachers as $key => $value) {
            //增加获取的对应响应时间数量及分数
            $teachers[$key] = array_merge($teachers[$key], CorrectService::getCorrectCount($value['uid'], $start_time, $end_time));
            //用于排序
            $grades[] = $teachers[$key]['correctcount'];
        }
        $array = [];
        foreach ($teachers as $keys => $values) {
            if ($values['notRedPenCount'] || $values['lessFortyCount'] || $values['netCommentsCount'] || $values['netPicCount']) {
                $array[$keys] = [
                    "uid" => $values['uid'],
                    "sname" => $values['sname'],
                    "avatar" => $values['avatar'],
                    "professionid" => $values['professionid'],
                    "genderid" => $values['genderid'],
                    "count" => $values['count'],
                    "correctcount" => $values['correctcount'],
                    "notRedPenCount" => $values['notRedPenCount'],
                    "lessFortyCount" => $values['lessFortyCount'],
                    "netCommentsCount" => $values['netCommentsCount'],
                    "genderid" => $values['genderid'],
                    "netPicCount" => $values['netPicCount']
                ];
                $gradess[] = $array[$keys]['correctcount'];
            }
        }
        //根据总分数排序数组
        if ($gradess) {
            array_multisort($gradess, SORT_DESC, $array);
        }
        return $this->controller->render('violations', ["models" => $array, "is_search" => 1, "search" => $search_con, "total_info" => $total_info, "subcount" => $subcount]);
    }

}
