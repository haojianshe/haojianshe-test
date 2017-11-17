<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;
use common\service\DictdataService;

/**
 * 精讲文章排序
 */
class AddTagNewsAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lecture';

    public function run() {
        $request = Yii::$app->request;
        $lecture_tagid = trim($request->get("lecture_tagid"));
        //分页获取精讲列表
        $data = LectureService::getByNewsPage($lecture_tagid);
        //添加分类信息用于前台显示
        $moedls = $data['models'];
        $data['models'] = $moedls;
        $data['s_catalog_id'] = $s_catalog_id;
        return $this->controller->render('add_tag_news', $data);
    }

}
