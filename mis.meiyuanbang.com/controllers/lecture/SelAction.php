<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;
use common\service\DictdataService;
use common\models\myb\LectureTagNews;

/**
 * 精讲主题列表
 */
class SelAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lecture';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = trim($request->get("f_catalog_id"));
        $title = trim($request->get("title"));
        $s_catalog_id = trim($request->get("s_catalog_id"));
        $idname = trim($request->get("idname"));
        $ztop = trim($request->get("ztop"));
        $newsid = trim($request->get("newsid"));
        $lecture_tagid = trim($request->get("lecture_tagid"));
        $news_data = trim($request->get("news_data"));
        $chk_value = trim($request->get("chk_value"));
        $newstype = 1; // trim($request->get("newstype"));
        if (!empty($news_data)) {
            $arr = [];
            $explodeArray = explode(',', $news_data);
            $newArray = array_filter($explodeArray);
        }
        //分页获取精讲列表
        $data = LectureService::getByPage($f_catalog_id, $title, $s_catalog_id, $idname, $ztop, $newstype,1);
        $res = LectureTagNews::findAll(['lecture_tagid' => $lecture_tagid]);

        //添加分类信息用于前台显示
        $moedls = $data['models'];
        foreach ($moedls as $k => $v) {
            $v['lecturetype'] = '';
            if ($v['lecture_level1']) {
                $v['lecturetype'] = DictdataService::getLectureMainTypeById($v['lecture_level1'])['maintypename'];
            }
            if ($v['lecture_level2']) {
                $v['lecturetype'] .= "--" . DictdataService::getLectureSubTypeById($v['lecture_level1'], $v['lecture_level2'])['subtypename'];
            }
            if (!empty($res)) {
                foreach ($res as $kk => $vv) {
                    if ($vv->newsid == $v['newsid']) {
                        $v['type'] = 1;
                    }
                }
            }
            $moedls[$k] = $v;
        }
        $data['models'] = $moedls;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['title'] = $title;
        $data['idname'] = $idname;
        $data['ztop'] = $ztop;
        $data['lecture_tagid'] = $lecture_tagid;
        $data['news_data'] = $news_data;
        $data['chk_value'] = $chk_value;
        $data['newstype'] = $newstype;
        $data['newArray'] = isset($newArray) ? $newArray : "";
        $data['s_catalog_id'] = $s_catalog_id;
        return $this->controller->render('sel', $data);
    }

}
