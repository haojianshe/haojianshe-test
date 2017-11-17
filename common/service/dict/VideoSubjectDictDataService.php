<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 一招
 */
class VideoSubjectDictDataService extends Object
{    
	
	/**
	 * 获取全部课程主类型
	 * @return multitype:number string
	 */
	static function getVideoSubjectMainType(){
		$ret = [				
				'1' => "技法",
				'2' => "名师",
				];
		return $ret;
	}
	
	/**
	 * 根据课程一级分类的名称获取id
	 * @return multitype:string
	 */
	static function getVideoSubjectMainTypeIdByName($name){
		$mainmodels = static::getVideoSubjectMainType();
		foreach ($mainmodels as $k=>$v){
			if($v == $name){
				return $k;
			}
		}
		return null;
	}
	
	/**
	 * 根据id取name
	 * @param unknown $id
	 * @return unknown|NULL
	 */
	static function getVideoSubjectMainTypeNameById($id){
		$mainmodels = static::getVideoSubjectMainType();
		foreach ($mainmodels as $k=>$v){
			if($k == $id){
				return $v;
			}
		}
		return null;
	}
	/**
	 * 接口获取分类
	 * @return [type] [description]
	 */
	public static function getCatalog(){
		$maintype=self::getVideoSubjectMainType();
		$ret=[];
		foreach ($maintype as $key => $value) {
			$temp['id']=$key;
			$temp['name']=$value;
			$ret[]=$temp;
		}
		return $ret;
	}
}
