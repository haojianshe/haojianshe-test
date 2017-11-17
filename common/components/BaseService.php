<?php
namespace common\components;
use Yii;
use yii\base\Object;

/**
 * 服务基类，用于在model之上封装业务逻辑，
 * yii自动生成的model中不在添加任何业务逻辑代码，以防数据库变动重新生成model时的不便
 * @author Administrator
 *
 */
class BaseService extends Object
{
	public function __construct()
	{
		parent::__construct();
	}
}