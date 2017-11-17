<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\UserService;
use mis\service\StudioMenuService;
use mis\service\PosidHomeUserService;

/**
 * 删除 审核 课程
 */
class DelAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $uid = $request->post('uid');
        $status = $request->post('status');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioService::findOne(['uid' => $uid]);
        $modelUser = UserService::findOne(['uid' => $uid]);
        //删除导航缓存
        self::cacheRedis($uid);
        if ($model) {
            if ($status == 1) {
                $statuss = 3;
                $modelUser->studio_type = 1;
                $modelUser->role_type = 3;
                $modelUser->save();
            } else if ($status == 4) {
                $statuss = 2;
                $modelUser->studio_type = 2;
                $modelUser->role_type = 1;
                $modelUser->save();
                StudioMenuService::deleteAll(['uid' => $uid]);
                PosidHomeUserService::deleteAll(['uid' => $uid, 'advert_type' => 2]);
            }
            $model->status = $statuss;
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

    //情空缓存
    public static function cacheRedis($uid) {
        $redis = Yii::$app->cache;
        $redis->delete('studio_menu_synopsis_' . $uid);
        $redis->delete('studio_menu_list_' . $uid);
        $redis->delete('user_detail_' . $uid);
    }

}
