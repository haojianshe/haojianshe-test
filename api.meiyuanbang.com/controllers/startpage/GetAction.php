<?php
namespace api\controllers\startpage;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StartpageService;
use api\service\HolidayIconsService;
use common\service\DictdataService;

class GetAction extends ApiBaseAction
{
    public function run()
    {



    	//(1)获取所有可用的启动页图片，添加100上限，预防数据太多
    	$ret=[];
    	$arrids = StartpageService::getIds();
    	if(! $arrids){
    		//当前并没有一个在有效期的启动图，取第一张图来返回给客户端，避免出错
    		$id = StartpageService::getFirstId();
    	}
    	else {
    		//随机获取一张启动图片,并获取信息
    		$index = rand(0, count($arrids)-1);
    		$id = $arrids[$index];
    	} 
    	$model = StartpageService::getByPageid($id);
    	//添加返回信息
    	$imgjson = json_decode($model['imginfo']);
    	$model['imginfo']=$imgjson;
        //节日图标
        $holidayicons=HolidayIconsService::getIconsDetail();
        if($holidayicons){
            $model['holidayicons_new']=$holidayicons;
        }
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $model);
    }
}
