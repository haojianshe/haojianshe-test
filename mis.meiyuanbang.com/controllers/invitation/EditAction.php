<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationActivityService;

/**
 * 编辑邀请活动
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
        $invitation_id = $request->get('invitation_id');
        if ($invitation_id) {
            //编辑
            if (!is_numeric($invitation_id)) {
                die('非法输入');
            }
        } else {
            //新添加
            $invitation_id = 0;
        }
        $ret = $this->getRetModel($invitation_id);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $model = new InvitationActivityService();
        if ($request->post('isedit') == 1) {
            $invitation_id = $request->post('invitation_id');
            $model = InvitationActivityService::findOne(['invitation_id' => $invitation_id]);
        } else {
            $invitation_id = 0;
        }
        if ($invitation_id) {
            //编辑判断
            $result = InvitationActivityService::find()
                    ->where(['<=', 'btime', strtotime($request->post('btime'))])
                    ->andWhere(['>=', 'award_time', strtotime($request->post('btime'))])
                    ->andWhere(['status' => 1])
                    ->andWhere(['<>', 'invitation_id', $invitation_id])
                    ->count();
            // ->createCommand()->getRawSql();
        } else {
            //添加判断
            $result = InvitationActivityService::find()
                    ->where(['<=', 'btime', strtotime($request->post('btime'))])
                    ->andWhere(['>=', 'award_time', strtotime($request->post('btime'))])
                    ->andWhere(['status' => 1])
                    ->count();
        }
        if ($result) {
            $ret['msg'] = '该时间段有正在进行的活动,不能再次添加！';
            $ret['isclose'] = true;
            return $ret;
        }

        //检查缩略图
        $thumb = $request->post('share_img');
        if ($thumb == '') {
            die('必须上传缩略图');
        }
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $model->load($request->post());
        $model->etime = strtotime($request->post('etime'));
        $model->btime = strtotime($request->post('btime'));
        //活动领奖截止时间
        $model->award_time = strtotime($request->post('award_time'));
        $model->activity_url = $request->post('activity_url');
        $model->activity_invitee_url = $request->post('activity_invitee_url');
        $model->honorees_instruction = $request->post('honorees_instruction');
        $model->invited_id = $request->post('invited_id');
        $model->prizes_ids = $request->post('prizes_ids');
        $model->share_title = $request->post('share_title');
        $model->share_img = $request->post('share_img');
        $model->share_desc = $request->post('share_desc');
        if (!$invitation_id) {
            $model->ctime = time();
        }
        $model->sms_copy = $request->post('sms_copy');
        $model->activity_rules = $request->post('activity_rules');
        $model->username = $usermodel->mis_realname;

        if ($model->save(true)) {
            $ret = $this->getRetModel($model->invitation_id);
            $redis = Yii::$app->cache;
            $rediskey = "invitation_list";
            $delete = $redis->delete($rediskey);
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
    private function getRetModel($invitation_id) {
        if ($invitation_id == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = InvitationActivityService::findOne(['invitation_id' => $invitation_id]);
            #  print_r($newmodel);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

}
