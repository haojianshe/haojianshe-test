<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioEnrollService;
use mis\service\StudioClasstypeService;
use mis\service\StudioMenuService;

/**
 * 编辑
 */
class SigneditAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $isclose = false;
        $msg = '';
        $classtypeid = $request->get('classtypeid');
        $uid = $request->get('uid');
        $t = $request->get('t');
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            if ($t > 0) {
                if (!is_numeric($classtypeid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = StudioEnrollService::findOne(['enrollid' => $t]);
                return $this->controller->render('sign_edit', ['model' => $model, 'msg' => $msg, 'uid' => $uid, 'classtypeid' => $classtypeid]); # , 'usersinfo' => $usersinfo
            } else {
                $model = new StudioEnrollService();
                return $this->controller->render('sign_edit', ['model' => $model, 'msg' => $msg, 'usersinfo' => [], 'uid' => $uid, 'classtypeid' => $classtypeid]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioEnrollService::findOne(['enrollid' => $request->post('StudioEnrollService')['enrollid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                $model->enroll_title = $request->post('StudioEnrollService')['enroll_title'];
            } else {
                //插入
                $model = new StudioEnrollService();
                $model->load($request->post());
                //$model->status = 3;
                //添加创建时间
                $model->ctime = time();
                $model->enroll_title = $request->post('StudioEnrollService')['enroll_title'];
                $model->classtypeid = $request->post('classtypeid');
                $model->uid = $request->post('uid');
            }
            if ($model->save()) {
                StudioMenuService::delCache($classtypeid, $uid);
                StudioClasstypeService::delCache($uid);
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('sign_edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'classtypeid' => $classtypeid]); # , 'usersinfo' => $usersinfo
        }
    }

}
