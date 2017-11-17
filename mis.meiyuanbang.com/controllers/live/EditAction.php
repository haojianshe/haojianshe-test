<?php

namespace mis\controllers\live;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\dict\LiveDictService;
use mis\service\LiveService;
use mis\service\UserService;
use common\models\myb\StudioTeacher;

class EditAction extends MBaseAction {

    public $resource_id = 'operation_video';

    public function run() {
        $request = Yii::$app->request;
        // 图片分类
        $config['imgmgr_level_1'] = LiveDictService::getCorrectMainType();
        $config['imgmgr_level_2'] = LiveDictService::getCorrectSubType();
        $userData = UserService::find()->select('sname,uid')->where(['ukind' => 1])->asArray()->all();
        $msg = '';
        $isclose = false;
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $id = $request->get('id');
            if ($id) {
                if (!is_numeric($id)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = LiveService::findOne(['liveid' => $id]);
                $usersinfo = UserService::getInfoByUids($model->teacheruid);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'users' => $userData, 'usersinfo' => $usersinfo]);
            } else {
                //add
                $model = new LiveService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'users' => $userData]);
            }
        } else {
            $customer_service = array(
                'qq'=>$request->post('qq'),
                'qq_name'=>$request->post('qq_name'),
                'qq_qun'=>$request->post('qq_qun'),
                'qq_qun_name'=>$request->post('qq_qun_name')
            );
            if ($request->post('isedit') == 1) {
                $model = LiveService::findOne(['liveid' => $request->post('LiveService')['liveid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //insert
                $model = new LiveService();
                $model->load($request->post());
                $usermodel = \Yii::$app->user->getIdentity();
                $model->username = $usermodel->mis_realname;
                $model->live_push_url = '0';
                $model->live_display_url = '0';
                $model->status = 1;
                $model->ctime = time();
            }
            $model->customer_service = json_encode($customer_service);
            $model->teacheruid = $request->post('teacheruid');
            $model->live_ios_price = $request->post('live_ios_price');
            $model->recording_ios_price = $request->post('recording_ios_price');
            $model->live_title = $request->post('live_title');
            $model->teacher_desc = $request->post('teacher_desc');
            $model->f_catalog_id = $request->post('f_catalog_id');
            $model->s_catalog_id = $request->post('s_catalog_id');
            $model->hits_basic = $request->post('hits_basic');
            $model->live_content = $request->post('live_content');
            $model->playtype = $request->post('playtype');
            $model->live_price = $request->post('live_price');
            $model->recording_price = $request->post('recording_price');
            $model->live_thumb_url = $request->post('live_thumb_url');
            $model->recording_thumb_url = $request->post('recording_thumb_url');
            $model->start_time = strtotime($request->post('start_time'));
            $model->end_time = strtotime($request->post('end_time'));
            $model->adminuid = $request->post('adminuid');
            $model->advid = $request->post('advid');
            $model->share_title = $request->post('share_title');
            $model->share_desc = $request->post('share_desc');
            $model->share_img = $request->post('share_img');

            if ($model->save()) {
                $redis = Yii::$app->cache;
                $redis_key = 'studio_live_list';
                $redis->delete($redis_key);
                LiveService::delCache($model->attributes['liveid'], $request->post('teacheruid'));
                //判断当前环境
                $tmp2 = 'com';
                $hostaddress = $_SERVER['HTTP_HOST'];
                if (strpos($hostaddress, '.meiyuanbang.com') === false) {
                    $tmp2 = 'cn';
                }
                $model = LiveService::findOne(['liveid' => $model->attributes['liveid']]);
                $key = '/myb/live' . $tmp2 . '_' . $model->attributes['liveid'] . '-' . $model->attributes['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
                $mdwKey = md5($key);
                $model->live_push_url = 'rtmp://video-center.alivecdn.com/myb/live' . $tmp2 . '_' . $model->attributes['liveid'] . '?vhost=live.meiyuanbang.com&auth_key=' . $model->attributes['end_time'] . '-0-0-' . $mdwKey;

                $displayKey = '/myb/live' . $tmp2 . '_' . $model->attributes['liveid'] . '.m3u8-' . $model->attributes['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
                $k = md5($displayKey);
                $model->live_display_url = 'http://live.meiyuanbang.com/myb/live' . $tmp2 . '_' . $model->attributes['liveid'] . '.m3u8?auth_key=' . $model->attributes['end_time'] . '-0-0-' . $k;
                $model->save();
                $redis = Yii::$app->cache;
                $redis_key = 'get_live_data_info_' . $request->post('LiveService')['liveid'];
                $redis->delete($redis_key);
                if ($request->post('isedit') == 1) {
                    $redis_key_tea = 'studio_studio_course' . $request->post('teacheruid'); //缓存key
                } else {
                    $redis_key_tea = 'studio_studio_course' . $model->attributes['teacheruid'];
                }
                $redis->delete("studio_coures_list");
                $redis->delete("studio_live_list");
                $redis->delete("live_list_" . $model->f_catalog_id);
                $redis->delete($redis_key_tea);
                $isclose = true;
                $msg = '保存成功';
            } else {
                $isclose = FALSE;
                $msg = '保存失败';
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]);
        }
    }

}
