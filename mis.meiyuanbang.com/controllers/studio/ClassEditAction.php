<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\StudioMenuService;
use mis\service\StudioClasstypeService;
use common\service\dict\CourseDictDataService;
use mis\service\UserService;

/**
 * 编辑
 */
class ClassEditAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        // 图片分类
        $config['imgmgr_level_1'] = CourseDictDataService::getCourseMainType();
        $config['imgmgr_level_2'] = CourseDictDataService::getCourseSubType();
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $classtypeid = $request->get('classtypeid');
            $uid = $request->get('uid');
            if ($classtypeid) {
                if (!is_numeric($classtypeid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = StudioClasstypeService::findOne(['classtypeid' => $classtypeid]);
                return $this->controller->render('class_edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'uid' => $uid]); # , 'usersinfo' => $usersinfo
            } else {
                $model = new StudioClasstypeService();
                return $this->controller->render('class_edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config, 'usersinfo' => [], 'uid' => $uid]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = StudioClasstypeService::findOne(['classtypeid' => $request->post('StudioClasstypeService')['classtypeid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                StudioMenuService::delCache($request->post('StudioClasstypeService')['classtypeid'], $request->post('uid'));
                StudioClasstypeService::delCache($request->post('uid'));
            } else {
                 //操作员
                $model = new StudioClasstypeService();
                $model->load($request->post());
                //添加创建时间
                $model->username = $usermodel->mis_realname;
                $model->ctime = time();
                //添加的时候循环删除所有班型的缓存
                $classTypeIds = StudioClasstypeService::find()->select('classtypeid')->where(['uid' => $request->post('uid')])->asArray()->all();
                foreach ($classTypeIds as $key => $val) {
                    StudioMenuService::delCache($val, $request->post('uid'));
                }
                StudioClasstypeService::delCache($request->post('uid'));
            }
             $model->uid = $request->post('uid');
             $model->class_desc =str_replace(array("\r\n", "\r", "\n"), "", $request->post('StudioClasstypeService')['class_desc']);
             #$model->class_desc = str_replace("\n","",trim($request->post('StudioClasstypeService')['class_desc']));
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('class_edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]); # , 'usersinfo' => $usersinfo
        }
    }

}
