<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
//use mis\service\MybActivityArticleService;
//use mis\service\NewsService;
//use mis\service\NewsDataService;
use mis\service\LkActivityService;
use mis\service\MybLkPaperService;
/**
 * 联考列表页
 */
class DetailsAction extends MBaseAction {
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //分页获取联考活动列表
        $data = MybLkPaperService::getByPage($request->get('lkid'));
        return $this->controller->render('details', $data);
    }

}
