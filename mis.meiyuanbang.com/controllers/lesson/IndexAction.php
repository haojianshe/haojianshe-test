<?php

namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonService;
use common\service\DictdataService;
use common\service\yj\DictDataService as dataservice;
/**
 * 跟着画列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lesson';

    public function run() {
        $request = Yii::$app->request;
        $title = trim($request->get("title")); #标题
        $f_catalog_id = trim($request->get("f_catalog_id")); #主分类
        $s_catalog_id = trim($request->get("s_catalog_id")); #二级分类
        //分页获取考点列表
        $data = LessonService::getByPage($title, $f_catalog_id, $s_catalog_id);
        $data['counts'] = LessonService::getLessonCount($title, $f_catalog_id, $s_catalog_id);
        //添加分类信息用于前台显示
        $moedls = $data['models'];
        foreach ($moedls as $k => $v) {
            $v['lessontype'] = '';
            if ($v['maintype']) {
                $v['lessontype'] = DictdataService::getLessonMainTypeById($v['maintype'])['maintypename'];
            }
            if ($v['subtype']) {
                $v['lessontype'] .= "--" . DictdataService::getLessonSubTypeById($v['maintype'], $v['subtype'])['subtypename'];
            }
            $moedls[$k] = $v;
        }

        $data['title'] = $title;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['s_catalog_id'] = $s_catalog_id;
        $data['models'] = $moedls;
        return $this->controller->render('index', $data);
    }

}
