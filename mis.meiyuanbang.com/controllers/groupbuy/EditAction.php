<?php

namespace mis\controllers\groupbuy;

use Yii;
use mis\components\MBaseAction;
use mis\service\GroupbuyService;
use mis\service\CourseService;
use common\service\DictdataService;

/**
 * 编辑
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        $isclose = false;
        $msg = '';
        $course_group_fee_ios = DictdataService::getIosProductPriceId();
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $groupbuyid = $request->get('groupbuyid');
            if ($groupbuyid) {
                if (!is_numeric($groupbuyid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = GroupbuyService::findOne(['groupbuyid' => $groupbuyid]);
                return $this->controller->render('edit', ['model' => $model, 'course_group_fee_ios' => $course_group_fee_ios, 'msg' => $msg,]);
            } else {
                $model = new GroupbuyService();
                return $this->controller->render('edit', ['model' => $model, 'course_group_fee_ios' => $course_group_fee_ios, 'msg' => $msg]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = GroupbuyService::findOne(['groupbuyid' => $request->post('GroupbuyService')['groupbuyid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new GroupbuyService();
                $model->load($request->post());
                //添加创建时间
                $model->ctime = time();
                $model->person_count_show = $request->post('GroupbuyService')['person_count_init'];
            }
            $courseArr = CourseService::find()->where(['courseid' => $request->post('GroupbuyService')['courseid']])->andWhere(['status' => 2])->andWhere(['buy_type' => 2])->count();
            if (!$courseArr) {
                $isclose = false;
                $msg = '所填写的课程id不是整课id';
                $model->start_time = strtotime($model->start_time);
                $model->end_time = strtotime($model->end_time);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'course_group_fee_ios' => $course_group_fee_ios]);
            }

            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            //判断所选课程是否已经参加过团购
            $buyEffCourse = GroupbuyService::find()
                    ->where(['courseid' => $request->post('GroupbuyService')['courseid']])
                    ->andWhere(['status' => 1]);
            if ($request->post('isedit') == 1) {
                $buyEffCourse->andWhere(['!=', 'groupbuyid', $request->post('GroupbuyService')['groupbuyid']]);
            }
            $buyEffCourse = $buyEffCourse->andWhere("( start_time<= $model->start_time and end_time >= $model->start_time) or (end_time<= $model->end_time and end_time >= $model->end_time)")
                    ->count();
            if ($buyEffCourse) {
                $isclose = false;
                $msg = '有没结束整课团购，团购结束后才能添加!';
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg]);
            }
            $model->course_group_fee_ios = $request->post('GroupbuyService')['course_group_fee_ios'];

            //操作员
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = json_encode($model->getErrors());
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }

}
