<?php
namespace api\components;

use Yii;
use common\components\BaseController;

/**
 * 
 * @author Administrator
 *
 */
class ApiBaseController extends BaseController
{
	//api项目不使用模板
	public $layout = false;
	//去掉csrf验证，不然post请求会被过滤掉
	public $enableCsrfValidation = false;
}