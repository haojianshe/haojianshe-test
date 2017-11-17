<?php

namespace mis\controllers\user;

use Yii;
use mis\components\MBaseAction;
use mis\service\YjUserService;

/**
 * 修改
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_zhn';

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
        $newsid = $request->get('uid');
      
        if ($newsid) {
            //编辑
            if (!is_numeric($newsid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $newsid = 0;
        }
        $ret = $this->getRetModel($newsid);
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
        if ($request->post('isedit') == 1) {
            $UserModel = YjUserService::findOne(['uid' => $request->post('uid')]);
            $newsid = $request->post('uid');
        } else {
            $newsid = 0;
            $UserModel = new YjUserService();
            $UserModel->status = 1;
            $UserModel->create_time = time();
        }
     
        $UserModel->umobile = $request->post('umobile');
        $UserModel->user_name = $request->post('user_name');
        $UserModel->user_age = $request->post('user_age');
        $UserModel->user_address = $request->post('user_address');
        $UserModel->sign_type = $request->post('sign_type');
        $UserModel->sign_time = strtotime($request->post('sign_time'));
        $UserModel->expe_time = strtotime($request->post('expe_time'));
        $UserModel->is_expe = $request->post('is_expe');
        $UserModel->is_sign = $request->post('is_sign');
        $UserModel->mark = $request->post('mark');
        //从公共方法先获取model并解析用户输入
        $ret = $this->getRetModel($newsid);
        //获取用户输入的界面
         $ret['UserModel'] = $UserModel;
        //保存
        if ($request->post('isedit') == 1) {
            //编辑保存
            if (!$UserModel->validate()) {
                $ret['msg'] = '输入错误，请检查输入项';
            } else {
                //保存
                if ($UserModel->save()) {
                    $ret['isclose'] = true;
                    $ret['msg'] = '保存成功';
                } else {
                    $ret['msg'] = '保存失败';
                }
            }
        } else {
            if ($UserModel->save(true)) {
                $ret['isclose'] = true;
                $ret['msg'] = '保存成功';
            } else {
                $ret['msg'] = '保存失败';
            }
        }
        return $ret;
    }

    /**
     * 根据newsid获取所有到的文章model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回文章编辑页的model
     */
    private function getRetModel($newsid) {
        $ret = [];
        if ($newsid == 0) {
            //获取精讲详细信息
            $yjUser = new YjUserService();
            $ret['UserModel'] = $yjUser;
        } else {
            $yjUser = YjUserService::findOne(['uid' => $newsid]);
            $ret['UserModel'] = $yjUser;
        }
        return $ret;
    }

}
