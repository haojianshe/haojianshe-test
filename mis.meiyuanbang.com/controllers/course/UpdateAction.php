<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectService;

/**
 * 编辑、添加 一招
 */
class UpdateAction extends MBaseAction {

    public $resource_id = 'operation_course';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $subjectid = $request->get('subjectid');
            if ($subjectid) {
                if (!is_numeric($subjectid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = VideoSubjectService::findOne(['subjectid' => $subjectid]);
                return $this->controller->render('update', ['model' => $model, 'msg' => $msg]);
            } else {
                $model = new VideoSubjectService();
                return $this->controller->render('update', ['model' => $model, 'msg' => $msg, 'usersinfo' => []]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = VideoSubjectService::findOne(['subjectid' => $request->post('VideoSubjectService')['subjectid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new VideoSubjectService();
                $model->load($request->post());
                //操作员
                $model->username = $usermodel->mis_realname;
                //添加创建时间
                $model->ctime = time();
            }
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('update', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }

}
