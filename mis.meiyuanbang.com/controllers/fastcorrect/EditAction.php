<?php

namespace mis\controllers\fastcorrect;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\FastCorrectService;
use mis\service\UserService;

/**
 * 编辑快速批改
 */
class EditAction extends MBaseAction {

    public function run() {
        $request = Yii::$app->request;
        $msg = '';
        $isclose = false;

        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $fastcorrectid = $request->get('fastcorrectid');
           
            if ($fastcorrectid) {
                //edit
                if (!is_numeric($fastcorrectid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = FastCorrectService::findOne(['fastcorrectid' => $fastcorrectid]);
                //老师信息
                $usersinfo = UserService::getInfoByUids($model->correct_teacheruids);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'usersinfo' => $usersinfo]);
            } else {
                //add
                $model = new FastCorrectService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'usersinfo' => ""]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //update
                $model = FastCorrectService::findOne(['fastcorrectid' => $request->post('FastCorrectService')['fastcorrectid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                $model->starttime = strtotime($request->post('FastCorrectService')['starttime']);
                $model->endtime = strtotime($request->post('FastCorrectService')['endtime']);
            } else {
                //insert
                $model = new FastCorrectService();
                $model->load($request->post());
                $model->activity_name = $request->post('FastCorrectService')['activity_name'];
                $model->starttime = strtotime($request->post('FastCorrectService')['starttime']);
                $model->endtime = strtotime($request->post('FastCorrectService')['endtime']);
                $model->ctime = time();
                $model->is_del = 0;
            }
            if (($model->endtime - $model->starttime) < 0) {
                $msg = '结束时间应该大于开始时间！！';
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'usersinfo' => ""]);
            }
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'usersinfo' => ""]);
        }
    }

}
