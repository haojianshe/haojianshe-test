<?php
namespace api\modules\v2_0_1\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use common\service\DictdataService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 求批改类型
 */
class CatalogGetAction extends ApiBaseAction
{
    public function run()
    {       
        //获取主类型 分类型 tag信息
    	$ret = DictdataService::getCorrectTypeAndTag()['data'];
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	
    	/** 减掉tag和某些分类型的代码备份
     	//素描
    	$tmp = $tweettype[3];
    	for($i=0;$i<count($tmp['catalog']);$i++){
    		unset($tmp['catalog'][$i]['tag_group']);
    		
    	}    	
    	unset($tmp['catalog'][9]);
    	$ret[] = $tmp;
    	*/
    }
}
