<?php
namespace api\modules\v3_0_2\controllers\lecture;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;
/**
 * 获取分类
 * @author ihziluoh
 *
 */
class GetCatalogAction extends ApiBaseAction{

   public  function run(){
	   	//一级分类
		$maintype=DictdataService::getLectureMainType();
		/*
		//二级分类
		foreach ($maintype as $key => $value) {
			$subtype=DictdataService::getLectureSubType($value['maintypeid']);
			if($subtype){
				$maintype[$key]['subtype']=$subtype;
			}else{
				$maintype[$key]['subtype']=[];
			}
			
		}*/
		$data=$maintype;
		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
	    
   }
}