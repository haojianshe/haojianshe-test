<?php
namespace api\components;

use Yii;
use common\components\BaseAction;

/**
 * 
 * @author Administrator
 *
 */
class ApiBaseAction extends BaseAction
{
	//当前用户id,在需要检查登录的action中,filter会给此变量赋值
	public $_uid = -1;
}