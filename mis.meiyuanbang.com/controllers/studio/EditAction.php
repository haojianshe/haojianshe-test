<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use common\service\dict\StudioDictDataService;
use mis\service\StudioMenuService;
use mis\service\StudioService;
use common\models\myb\StudioTeacher;

/**
 * 编辑
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        // 图片分类
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $uid = $request->get('uid');
            if (is_numeric($uid)) {
                //根据id取出数据
                $model = StudioService::findOne(['uid' => $uid]);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg]);
            } else {
                $model = new StudioService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'usersinfo' => []]);
            }
        } else {
            StudioService::delCache($uid);
            // StudioMenuService::delCache($uid);
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioService::findOne(['uid' => $request->post('StudioService')['uid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new StudioService();
                $model->load($request->post());
                //$model->status = 3;
                //添加创建时间
                $model->ctime = time();
            }
            //操作员
            $model->username = $usermodel->mis_realname;
            if ($model->save()) {
                $u_id = $request->post('StudioService')['uid'] ? $request->post('StudioService')['uid'] : $uid;
                #echo $u_id;
                $studioModel = StudioMenuService::findOne(['uid' => $u_id]);
                //如果
                if (empty($studioModel->uid)) {
                    $connection = Yii::$app->db;
                    $str = " insert into myb_studio_menu (menuid,uid,menu_type,ctime) values ";
                    foreach (StudioDictDataService::getBookMainType() as $key => $val) {
                        if ($key <= 5) {
                            $str .="('$key','$u_id','1','" . time() . "'),";
                        }
                    }
                    $newstr = substr($str, 0, strlen($str) - 1) . ';';
                    $commandSql = $connection->createCommand($newstr);
                    $commandSql->execute();
                }
                $studioTeacherModel = StudioTeacher::findOne(['uid' => $u_id, 'uuid' => $u_id]);
                if (empty($studioTeacherModel)) {
                    $connection = Yii::$app->db;
                    $myb_studio_teacher = "insert into myb_studio_teacher (uid,uuid,ctime) values ($u_id,$u_id," . time() . ")";
                    $command = $connection->createCommand($myb_studio_teacher);
                    $command->execute();
                }
                
                $redis = \Yii::$app->cache;
                $redis->delete('studio_menu_list_' . $request->post('StudioService')['uid']);
                
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            // $usersinfo = UserService::getInfoByUids($model->teacheruid);
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]);
        }
    }

}
