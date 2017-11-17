<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 批改变作品原因字典数据
 */
class CorrectChangeReasonService extends Object
{  
	/**
	 * 获取全部转作品原因
	 * @return multitype:multitype:number string
	 */
	static function getReasonList(){
		$ret = [['reasonid'=>1,'reasondesc'=>'不在批改范围'],
				['reasonid'=>2,'reasondesc'=>'不是原创']];
		return $ret;
	}
	
	/**
	 * 根据id获取实例
	 * @return multitype:string
	 */
	static function getModelById($resonId){
		$resonlist = static::getReasonList();
		foreach ($resonlist as $k=>$v){
			if($v['reasonid'] == $resonId){
				return $v;
			}
		}
		return null;
	}
}
