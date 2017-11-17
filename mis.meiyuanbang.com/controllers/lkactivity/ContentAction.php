<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybActivityArticleService;

/**
 * 联考编辑文章
 * 
 */
class ContentAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //分页获取联考文章列表页
        $lkid = $request->get('lkid');
        $zp_type = $request->get('zp_type');
        if ($zp_type==3) {
            $data = MybActivityArticleService::getByPage($lkid,$zp_type);
            return $this->controller->render('article', $data);
        } else if($zp_type==2) {
            $data = MybActivityArticleService::getByPage($lkid,$zp_type);
            return $this->controller->render('article', $data);
        }
    }

}
