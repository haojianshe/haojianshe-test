<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioArticleService;

/**
 * 画室导航排序
 */
class ArticleListorderAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $articleid = $request->post('articleid');
        $uid = $request->post('uid');
        $menuid = $request->post('menuid');

        $value = $request->post('value');
        if (!$articleid || !is_numeric($articleid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioArticleService::findOne(['articleid' => $articleid]);

        $redis = Yii::$app->cache;
        $redis->delete('studio_menu_list_' . $uid);
        $redis->delete('studio_teacher_list_' . $uid . '_menutype_' . $menuid);
        if ($model) {
            //修改文章排序
            if ($value == '1.11') {
                $model->status = 2;
            } else {
                $model->listorder = $value;
            }
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

}
