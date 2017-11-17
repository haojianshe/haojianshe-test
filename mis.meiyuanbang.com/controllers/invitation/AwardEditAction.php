<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationAwardRecordService;

/**
 * 编辑邀请活动领奖记录
 */
class AwardEditAction extends MBaseAction {

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
        return $this->controller->render('award_edit', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $award_id = $request->get('award_id');
        if ($award_id) {
            //编辑
            if (!is_numeric($award_id)) {
                die('非法输入');
            }
        } else {
            //新添加
            $award_id = 0;
        }
        $ret = $this->getRetModel($award_id);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $model = new InvitationAwardRecordService();
        if ($request->post('isedit') == 1) {
            $award_id = $request->post('award_id');
            $model = InvitationAwardRecordService::findOne(['award_id' => $award_id]);
        } else {
            $award_id = 0;
        }
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $model->load($request->post());
        $model->comment = $request->post('comment');
        $model->handle_time = time();
        $model->status = 2;
        $model->username = $usermodel->mis_realname;

        if ($model->save(true)) {
            $ret = $this->getRetModel($model->award_id);
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
    private function getRetModel($award_id) {
        if ($award_id == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = InvitationAwardRecordService::findOne(['award_id' => $award_id]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
