<?php

namespace mis\controllers\reward;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkPrizesService;

/**
 * 奖品添加和修改页面
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_activity';
    //活动在news表中的catid值
    private $activitycatid = 2;

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
        $prizesid = $request->get('prizesid');
        if ($prizesid) {
            //编辑
            if (!is_numeric($prizesid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $prizesid = 0;
        }
        $ret = $this->getRetModel($prizesid);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        $msg = '';
        $usermodel = \Yii::$app->user->getIdentity();
        //先获取model
        $model = new DkPrizesService();
        if ($request->post('isedit') == 1) {
            $prizesid = $request->post('prizesid');
            $model = DkPrizesService::findOne(['prizesid' => $prizesid]);
        } else {
            $prizesid = 0;
        }

        //检查缩略图
        $thumb = $request->post('thumb');
        if ($thumb == '') {
            die('必须上传缩略图');
        }
        //insert
        $model->load($request->post());
        $model->title = $request->post('title');
        $model->type = $request->post('type');
        $model->img = $request->post('thumb');
        if ($request->post('type') == 1 || $request->post('type') == 2) {
            $model->content = $request->post('content');
        } else {
            $model->content = '';
        }

        $model->ctime = time();
        $model->status = 1;
        if ($model->save(true)) {
            $ret = $this->getRetModel($model->prizesid);
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            $ret['msg'] = '保存失败';
        }
        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($prizesid) {
        if ($prizesid == 0) {
            //获取精讲详细信息
            $ret['activitymodel'] = '';
            $ret['newsmodel'] = '';
            $ret['newsdatamodel'] = '';
            $ret['thumb_url'] = '';
            $ret['model'] = '';
        } else {
            $newmodel = DkPrizesService::findOne(['prizesid' => $prizesid]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
