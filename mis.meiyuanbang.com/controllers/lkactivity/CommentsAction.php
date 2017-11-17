<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
//use mis\service\MybActivityArticleService;
use mis\service\NewsService;
//use mis\service\NewsDataService;
// use mis\service\LkActivityService;
// use mis\service\MybLkPaperService;
/**
 * 文章评论列表页面
 */
class CommentsAction extends MBaseAction {
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //分页获取文章评论列表页面
        $data = NewsService::getByPage($request->get('newsid'));
        return $this->controller->render('comments', $data);
    }

}
