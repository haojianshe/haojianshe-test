<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;

/**
 * 编辑联考活动
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
        } else {
            //处理post请求
            $ret = $this->postHandle();
        }
        return $this->controller->render('edit', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $lkid_id = $request->get('activityid');
        if ($lkid_id) {
            //编辑
            if (!is_numeric($lkid_id)) {
                die('非法输入');
            }
        } else {
            //新添加
            $lkid_id = 0;
        }
        $ret = $this->getRetModel($lkid_id);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $model = new LkActivityService();
        if ($request->post('isedit') == 1) {
            $lkid_id = $request->post('lkid_id');
            $model = LkActivityService::findOne(['lkid' => $lkid_id]);
        } else {
            $lkid_id = 0;
        }
        //检查缩略图
        $thumb = $request->post('share_img');
        if ($thumb == '') {
            die('必须上传缩略图');
        }
        $usermodel = \Yii::$app->user->getIdentity();
        if (empty($lkid_id)) {
            $model->newsid = 0;
        }
        //写入/insert
        $model->load($request->post());
        $model->title = $request->post('title');
        $model->share_desc = $request->post('share_desc');
        $model->share_img = $request->post('share_img');
        $model->share_title = $request->post('share_title');
        $model->ctime = time();
        $model->btime = strtotime($request->post('btime'));
        $model->provinceid = $request->post('provinceid');
        # $model->status = 1;
        #$model->rank_status = 1;
        $model->adminid = $usermodel->mis_userid;
        #$model->activity_type = 1;
        if ($model->save(true)) {
            $ret = $this->getRetModel($model->lkid);
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
            //修改完毕情况缓存
            if ($request->post('isedit') == 1) {
                $redis = Yii::$app->cache;
                $rediskey = "lk_" . $request->post('lkid_id');
                $redis->delete($rediskey);
            }
        } else {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = false;
        }
        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($lkid_id) {
        if ($lkid_id == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = LkActivityService::findOne(['lkid' => $lkid_id]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
