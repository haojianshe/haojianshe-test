<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseService;
use common\service\dict\CourseDictDataService;
use mis\service\UserService;
use mis\service\TurntableGameService;
use mis\service\VideoResourceService;

/**
 * 编辑
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_course';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        // 图片分类
        $config['imgmgr_level_1'] = CourseDictDataService::getCourseMainType();
        $config['imgmgr_level_2'] = CourseDictDataService::getCourseSubType();
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $courseid = $request->get('courseid');
            if ($courseid) {
                if (!is_numeric($courseid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = CourseService::findOne(['courseid' => $courseid]);
                $usersinfo = UserService::getInfoByUids($model->teacheruid);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'usersinfo' => $usersinfo]);
            } else {
                $model = new CourseService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'usersinfo' => []]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = CourseService::findOne(['courseid' => $request->post('CourseService')['courseid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new CourseService();
                $model->load($request->post());
                //添加创建时间
                $model->ctime = time();
            }
            //是否存在活动
            if ($model->gameid) {
                if (!TurntableGameService::getGameCount($model->gameid)) {
                    $isclose = false;
                    $msg = '抽奖活动id不存在，请您核实后再添加。';
                    return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'usersinfo' => $usersinfo]);
                }
            }
            //查看课程视频详情是否存在
            if ($model->learn_videoid) {
                $videoResource = VideoResourceService::find()->where(['videoid' => $model->learn_videoid])->count();
                if (!$videoResource) {
                    $isclose = false;
                    $msg = '视频不存在';
                    return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'usersinfo' => $usersinfo]);
                }
            }

            $customer_service = array(
                'qq' => $request->post('qq'),
                'qq_name' => $request->post('qq_name'),
                'qq_qun' => $request->post('qq_qun'),
                'qq_qun_name' => $request->post('qq_qun_name')
            );
          
            $model->customer_service = json_encode($customer_service);
            //操作员
            $model->username = $usermodel->mis_realname;
            if ($request->post('CourseService')['game_start_time']) {
                $model->game_start_time = strtotime($request->post('CourseService')['game_start_time']);
                $model->game_end_time = strtotime($request->post('CourseService')['game_end_time']);
            }

            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            $usersinfo = UserService::getInfoByUids($model->teacheruid);
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'usersinfo' => $usersinfo]);
        }
    }

}
