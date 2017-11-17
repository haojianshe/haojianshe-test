<?php
namespace mis\controllers\main;

use Yii;
use yii\base\Action;
/**
 * mis框架页
 */
class DefaultAction extends Action
{
	/**
	 * 取到用户的信息，返回给前台展示
	 */
    public function run()
    {
    	//欢迎页使用layout
    	$this->controller->layout = 'frameinner';
    	return $this->controller->render('default');    	
    }
}
