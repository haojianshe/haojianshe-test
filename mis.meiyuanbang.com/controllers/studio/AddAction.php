<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\UserService;
use mis\service\StudioMenuService;

/**
 * 编辑
 */
class AddAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {

        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }

        //检查参数是否非法
        $uid = $request->post('uid');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        $model = StudioService::findOne(['uid' => $uid]);
        if (empty($model)) {
            //根据id取出数据
            $model = new StudioService();
            $model->status = 1;
            $model->uid = $uid;
            $model->studio_desc = '0';
            $model->ctime = time();
            //审核判断章节视频是否为空
            //获取章节
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        } elseif ($model->status == 2) {
            $modelUser = UserService::findOne(['uid' => $uid]);
            $model->status = 1;
            $ret = $model->save();
            $modelUser->studio_type = 0;
            $modelUser->save();
            StudioService::delCache($uid);
            StudioMenuService::delCache('',$uid);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        } elseif ($model->status == 3 || $model->status == 1) {
            $model->status = 1;
            $ret = $model->save();
            StudioService::delCache($uid);
            StudioMenuService::delCache('',$uid);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 1, 'msg' => '已经添加过该用户']);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '添加失败']);
    }

}
