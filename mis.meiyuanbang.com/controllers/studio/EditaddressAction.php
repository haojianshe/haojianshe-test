<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\StudioAddressService;
use common\service\dict\CourseDictDataService;
use mis\service\UserService;

/**
 * 编辑
 */
class EditaddressAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {

        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        // 图片分类
        $addrid = $request->get('addrid');
        $uid = $request->get('uid');
        $type = $request->get('s');
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            if ($uid) {
                if (!is_numeric($uid)) {
                    die('非法输入');
                }
                if ($type == 1) {
                    //根据id取出数据
                    $model = StudioAddressService::findOne(['addrid' => $addrid]);
                    # $usersinfo = UserService::getInfoByUids($model->teacheruid);
                    return $this->controller->render('edit_address', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'uid' => $uid]); # , 'usersinfo' => $usersinfo
                } else {
                    $model = new StudioAddressService();
                    return $this->controller->render('edit_address', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'usersinfo' => [], 'uid' => $uid]);
                }
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioAddressService::findOne(['addrid' => $request->post('StudioAddressService')['addrid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new StudioAddressService();
                $model->load($request->post());
                //添加创建时间
                $model->ctime = time();
                $model->status = 1;
                $model->uid = $request->post('uid');
            }
            //操作员
            #$model->uid =$request->post('uid');
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            # $usersinfo = UserService::getInfoByUids($model->teacheruid);
            return $this->controller->render('edit_address', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]); # , 'usersinfo' => $usersinfo
        }
    }

}
