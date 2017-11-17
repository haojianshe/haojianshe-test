<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;

/**
 * 编辑广告/增加
 */
class EditadvertAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
        } else {
            //处理post请求
            $ret = $this->postHandle();
        }
        $ret['uid'] = $request->get('uid');
        return $this->controller->render('editadvert', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $posidid = $request->get('posidid');
        if ($posidid) {
            //编辑
            if (!is_numeric($posidid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $posidid = 0;
        }
        $ret = $this->getRetModel($posidid);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $model = new PosidHomeUserService();
        $uid = $request->post('uidid');
        if ($request->post('isedit') == 1) {
            $posidid = $request->post('posidid');
            $model = PosidHomeUserService::findOne(['posidid' => $posidid]);
        } else {
            $posidid = 0;
        }

        //检查缩略图
        $thumb = $request->post('img');
        if ($thumb == '') {
            die('必须上传缩略图');
        }
        //写入/insert
        $model->load($request->post());
        $model->uid = $uid;
        $model->url = $request->post('url');
        $model->img = $request->post('img');
        $model->listorder = $request->post('listorder');
        $model->ctime = time();
        $model->status = 1;
        $model->advert_type = 2;
        PosidHomeUserService::delCache($uid);
        if ($model->save(true)) {
            $ret = $this->getRetModel($model->posidid);
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = false;
        }
        return $ret;
    }

    /**
     * 根据广告表中的数据
     */
    private function getRetModel($posidid) {
        if ($posidid == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = PosidHomeUserService::findOne(['posidid' => $posidid]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
