<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;
use common\service\DictdataService;
use mis\service\MisUserService;

/**
 * 精讲列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lecture';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = trim($request->get("f_catalog_id")); #主分类
        $s_catalog_id = trim($request->get("s_catalog_id")); #二级分类
        $title = trim($request->get("title")); #标题
        $idname = trim($request->get("idname")); #id
        $ztop = trim($request->get("ztop")); #置顶
        $status = trim($request->get("status")); #审核
        $newstype = trim($request->get("newstype"));
        $adminuser = trim($request->get("adminuser")); #发布人
        $start_time = trim($request->get("start_time")); #开始时间
        $end_time = trim($request->get("end_time")); #结束时间
        #print_r($userData);
        //分页获取精讲列表
        $data = LectureService::getByPage($f_catalog_id, $title, $s_catalog_id, $idname, $ztop, $newstype, $status, $adminuser, $start_time, $end_time);

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
            $moedls[$k] = $v;
        }
        $data['models'] = $moedls;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['title'] = $title;
        $data['idname'] = $idname;
        $data['ztop'] = $ztop;
        $data['newstype'] = $newstype;
        $data['s_catalog_id'] = $s_catalog_id;
        $data['status'] = $status;
        $data['adminuser'] = $adminuser;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        //发布人
        $data['userData'] = MisUserService::getUserDetail();
        return $this->controller->render('index', $data);
    }

}
