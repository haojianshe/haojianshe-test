<?php
namespace api\modules\v2_2_0\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\MaterialSubjectService;
use api\lib\enumcommon\ReturnCodeEnum;


/**
 * 专题下划获取最新
 */
class SubjectGetNewAction extends ApiBaseAction
{
	public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//(1)获取最新的专题列表
    	$mids = MaterialSubjectService::getMaterialList(0, $rn);
    	//(2)获取每个专题的详细信息
        $ret=[];
    	foreach($mids as $mid){
             $tmp = MaterialSubjectService::getMaterialDetail($mid);
             if($tmp){
                $tmp["picurl"]=json_decode($tmp["picurl"]);
             	$ret[]= $tmp;
             }             
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$ret]);
    }
}
