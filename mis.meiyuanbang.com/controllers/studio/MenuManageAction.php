<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioArticleService;

/**
 * 列表页
 */
class MenuManageAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {

        //分页列表
        $request = Yii::$app->request;
        //检查参数是否非法
        $uid = $request->get('uid');
        $menuid = $request->get('menuid');
        $studiomenuid= $request->get('studiomenuid');
        $data = StudioArticleService::getByPage($studiomenuid);
        return $this->controller->render('menumanage_list', ['models'=>$data,'studiomenuid'=>$studiomenuid,'menuid'=>$menuid,'uid'=>$uid]);
    }

}
