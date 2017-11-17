<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PublishingBookService;

/**
 * 推荐管理列表
 */
class RecommendedAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $type = $request->get('type');
        
        if(!isset($type)){
            $type =0;
        }
        //分页获取图书列表
        $data = PublishingBookService::getBookList($uid,$type);
        return $this->controller->render('recommended', $data);
    }

}
