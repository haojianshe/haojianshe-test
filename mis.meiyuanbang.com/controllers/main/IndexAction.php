<?php
namespace mis\controllers\main;

use Yii;
use yii\base\Action;

/**
 * mis框架页
 */
class IndexAction extends Action
{
	/**
	 * 取到用户的信息，返回给前台展示
	 */
    public function run()
    {
    	$model = \Yii::$app->user->getIdentity();
    	return $this->controller->render('index', ['realname' => $model->mis_realname,'model'=>$model]);    	
    }
}