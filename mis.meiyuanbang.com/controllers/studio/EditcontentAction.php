<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\StudioEnrollService;
use mis\service\StudioMenuService;

/**
 * 画室简介编辑
 */
class EditcontentAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        $uid = $request->get('uid');

        // 图片分类
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            if (is_numeric($uid)) {
                //根据id取出数据
                $model = StudioService::findOne(['uid' => $uid]);
                return $this->controller->render('edit_content', ['model' => $model, 'msg' => $msg]);
            } else {
                $model = new StudioService();
                return $this->controller->render('edit_content', ['model' => $model, 'msg' => $msg, 'usersinfo' => []]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioService::findOne(['uid' => $request->post('StudioService')['uid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new StudioService();
                $model->load($request->post());
            }
            //情况画室的内容 缓存
            StudioService::delCache($uid);
            if ($model->save()) {
                $enroll = StudioEnrollService::find()->distinct()->select('classtypeid')->where(['uid' => $uid])->asArray()->all();
                foreach ($enroll as $key => $val) {
                    //清空班型的缓存
                    StudioMenuService::delCache($val['classtypeid'], $uid);
                }
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            // $usersinfo = UserService::getInfoByUids($model->teacheruid);
            return $this->controller->render('edit_content', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]);
        }
    }

}
