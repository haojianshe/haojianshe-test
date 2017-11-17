<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioOpusService;
#use common\service\dict\CourseDictDataService;
use mis\service\ResourceService;

/**
 * 编辑
 */
class EditopusAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {

        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        //get访问，判断是edit还是add,返回不同界面
        $studioopusid = $request->get('studioopusid');
        $studiomenuid = $request->get('studiomenuid');
        $uid = $request->get('uid');
        // 图片分类
        if (!$request->isPost) {
            if (!is_numeric($studiomenuid)) {
                die('非法输入');
            }
            if ($studioopusid) {
                //根据id取出数据
                $model = StudioOpusService::find()->select('studioopusid,studiomenuid,uid,resourceid as resid,ctime,listorder,status,opus_title')->where(['studioopusid' => $studioopusid])->asArray()->one();
                $model['resourceid'] = json_decode(ResourceService::find()->select('img')->where(['rid' => $model['resid']])->asArray()->one()['img'], 1)['n']['url'];
                return $this->controller->render('edit_opus', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'uid' => $uid, 'res' => $res, 'studiomenuid' => $studiomenuid]); # , 'usersinfo' => $usersinfo
            } else {
                $model = new StudioOpusService();
                return $this->controller->render('edit_opus', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'usersinfo' => [], 'uid' => $uid, 'studiomenuid' => $studiomenuid]);
            }
        } else {
            $redis = \Yii::$app->cache;
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioOpusService::findOne(['studioopusid' => $request->post('StudioOpusService')['studioopusid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());

                $redis_key = "myb_studio_opus_" . $request->post('uid');
                $redis_key_opusid = "studio_opus_" . $request->post('StudioOpusService')['studioopusid'];
                $redis->delete($redis_key);
                $redis->delete($redis_key_opusid);
            } else {
                //插入
                $model = new StudioOpusService();
                $model->load($request->post());
                //添加创建时间
                $model->ctime = time();
                $model->status = 1;
            }
            $model->opus_title = $request->post('StudioOpusService')['opus_title'];
            $model->listorder = $request->post('StudioOpusService')['listorder'];
            $model->uid = $request->post('uid');
            $model->studiomenuid = $request->post('studiomenuid');
         
            //操作员
            if ($_SESSION['rcid']) {
                $model->resourceid = $_SESSION['rcid'];
            }else{
                 $model->resourceid = $request->post('resid');
            }
            $redis_key = "myb_studio_opus_" . $request->post('uid');
            $redis_key_opusid = "studio_opus_" . $request->post('studioopusid');
            $redis->delete($redis_key);
            $redis->delete($redis_key_opusid);
            $redis->delete('studio_picture_list_'.$request->post('uid'));
            if ($model->save()) {
                unset($_SESSION['rcid']);
                unset($_SESSION['studio_img_json']);
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('edit_opus', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, 'studiomenuid' => $studiomenuid]); # , 'usersinfo' => $usersinfo
        }
    }

}
