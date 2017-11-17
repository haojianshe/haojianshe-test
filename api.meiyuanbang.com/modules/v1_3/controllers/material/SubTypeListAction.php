<?php
namespace api\modules\v1_3\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 根据帖子主类型获取所有分类型信息
 */
class SubTypeListAction extends ApiBaseAction
{
	public function run()
    {
    	//获取主类型id，判断是否为数字
    	$mlevel = $this->requestParam('mlevel',true);
    	if(!is_numeric($mlevel)){
    		die('参数错误');
    	}
    	$ret = [];
    	//(1)取出对应的所有分类型
		$subs = DictdataService::getTweetSubType()[$mlevel];
    	//(2)为每个分类型添加tags信息(或者不填)
    	if($subs){
    		foreach ($subs as $k=>$v){
    			$tag_group = DictdataService::getTweetSubTypeTags($k);
    			$sub = ['id'=>$k,'name'=>$v,'tag_group'=>$tag_group];
    			$ret[] = $sub;
    		}
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
