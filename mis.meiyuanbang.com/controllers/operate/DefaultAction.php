<?php
namespace mis\controllers\operate;

use Yii;
use yii\base\Action;
/**
 * 运营欢迎页
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
