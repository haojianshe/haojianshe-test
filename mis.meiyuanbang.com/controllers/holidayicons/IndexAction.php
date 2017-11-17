<?php
namespace mis\controllers\holidayicons;

use Yii;
use mis\components\MBaseAction;
use mis\service\HolidayIconsService;
use common\service\DictdataService;

/**
 * 节日图标管理
 */
class IndexAction extends MBaseAction
{
	public $resource_id = 'operation_icons';
	public function run()
    {
    	//分页获取节日图标列表
    	$data =  HolidayIconsService::getDataByPage();
    	return $this->controller->render('index',$data);
    }
}
