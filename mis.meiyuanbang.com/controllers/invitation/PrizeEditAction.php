<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationPrizesService;

/**
 * 编辑邀请活动奖品
 */
class PrizeEditAction extends MBaseAction {

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
        return $this->controller->render('prize_edit', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $prizes_id = $request->get('prizes_id');
        if ($prizes_id) {
            //编辑
            if (!is_numeric($prizes_id)) {
                die('非法输入');
            }
        } else {
            //新添加
            $prizes_id = 0;
        }
        $ret = $this->getRetModel($prizes_id);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $model = new InvitationPrizesService();
        if ($request->post('isedit') == 1) {
            $prizes_id = $request->post('prizes_id');
            $model = InvitationPrizesService::findOne(['prizes_id' => $prizes_id]);
        } else {
            $prizes_id = 0;
        }
        //检查缩略图
        $thumb = $request->post('img');
        if ($thumb == '') {
            die('必须上传缩略图');
        }
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $model->load($request->post());
        $model->title = $request->post('title');
        $model->prizes_type = $request->post('prizes_type');
        $model->img = $request->post('img');
        $model->number = $request->post('number');
        if (!$prizes_id) {
            $model->ctime = time();
        }
        $model->username = $usermodel->mis_realname;

        if ($model->save(true)) {
            $ret = $this->getRetModel($model->prizes_id);
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = true;
        }
        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($prizes_id) {
        if ($prizes_id == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = InvitationPrizesService::findOne(['prizes_id' => $prizes_id]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
