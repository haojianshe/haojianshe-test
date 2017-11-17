<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 素材字典数据，主要配置给类型素材首页
 */
class MaterialDictDataService extends Object
{  
	/**
	 * 获取全部课程类型
	 * @return multitype:number string
	 */
	static function getDisplayCountByMainType($maintypeid){
		$ret = null;
		switch ($maintypeid){
			//素描			
			case 4:
				$ret=[
						['subtypeid'=>126,'subtypename'=>'组合静物','displaycount'=>4],
						['subtypeid'=>128,'subtypename'=>'石膏像','displaycount'=>4],
						['subtypeid'=>129,'subtypename'=>'头像','displaycount'=>2],
						['subtypeid'=>125,'subtypename'=>'单体静物','displaycount'=>3],
						['subtypeid'=>123,'subtypename'=>'单体几何','displaycount'=>2],
						['subtypeid'=>124,'subtypename'=>'组合几何','displaycount'=>3],
						['subtypeid'=>130,'subtypename'=>'半身像','displaycount'=>2],
						['subtypeid'=>131,'subtypename'=>'人体解剖','displaycount'=>2],
						['subtypeid'=>4003,'subtypename'=>'全身像','displaycount'=>3],
						['subtypeid'=>4004,'subtypename'=>'场景','displaycount'=>2],
						['subtypeid'=>4006,'subtypename'=>'动物','displaycount'=>1],
				];
				break;
			//色彩
			case 1:
				$ret=[
						['subtypeid'=>100,'subtypename'=>'组合静物','displaycount'=>4],
						['subtypeid'=>102,'subtypename'=>'头像','displaycount'=>2],
						['subtypeid'=>101,'subtypename'=>'单体静物','displaycount'=>3],
						['subtypeid'=>'1003','subtypename'=>'场景','displaycount'=>2],
						['subtypeid'=>103,'subtypename'=>'风景','displaycount'=>3],
						['subtypeid'=>'1002','subtypename'=>'全身像','displaycount'=>1],
						['subtypeid'=>'1001','subtypename'=>'半身像','displaycount'=>4],
				];
				break;
			//速写
			case 5:
				$ret=[
						['subtypeid'=>133,'subtypename'=>'人物速写','displaycount'=>4],
						['subtypeid'=>137,'subtypename'=>'场景速写','displaycount'=>3],						
						['subtypeid'=>134,'subtypename'=>'人物局部速写','displaycount'=>4],						
						['subtypeid'=>136,'subtypename'=>'人体结构','displaycount'=>4],
						['subtypeid'=>138,'subtypename'=>'命题速写','displaycount'=>2],					
						['subtypeid'=>135,'subtypename'=>'动态快写','displaycount'=>1],
						['subtypeid'=>5001,'subtypename'=>'人物半身速写','displaycount'=>2],
						['subtypeid'=>5003,'subtypename'=>'动物','displaycount'=>1],
						['subtypeid'=>5004,'subtypename'=>'道具','displaycount'=>1],
				];
				break;
			//设计
			case 2:
				$ret=[
						['subtypeid'=>2003,'subtypename'=>"黑白装饰画",'displaycount'=>4],
						['subtypeid'=>107,'subtypename'=>'单体装饰画','displaycount'=>3],
						['subtypeid'=>2002,'subtypename'=>"彩色装饰画",'displaycount'=>3],
						['subtypeid'=>110,'subtypename'=>'命题创意速写','displaycount'=>2],
						['subtypeid'=>112,'subtypename'=>'设计素描','displaycount'=>4],
						['subtypeid'=>113,'subtypename'=>'设计色彩','displaycount'=>4],
						['subtypeid'=>114,'subtypename'=>'平面设计','displaycount'=>2],
						['subtypeid'=>111,'subtypename'=>'字体设计','displaycount'=>1],
				];
				break;
			//创作
			case 6:
				$ret=[
						['subtypeid'=>6001,'subtypename'=>'室内','displaycount'=>3],
						['subtypeid'=>6002,'subtypename'=>'室外','displaycount'=>2],
				];
				break;
			//照片
			case 3:
				$ret=[
						['subtypeid'=>3010,'subtypename'=>'头像','displaycount'=>4],
						['subtypeid'=>3004,'subtypename'=>'组合静物','displaycount'=>4],
						['subtypeid'=>3012,'subtypename'=>'全身像','displaycount'=>3],
						['subtypeid'=>3003,'subtypename'=>'单体静物','displaycount'=>3],
						['subtypeid'=>3011,'subtypename'=>'半身像','displaycount'=>2],
						['subtypeid'=>117,'subtypename' => "场景",'displaycount'=>3],
						['subtypeid'=>3014,'subtypename'=>'人物场景','displaycount'=>4],
						['subtypeid'=>3007,'subtypename'=>'石膏人像','displaycount'=>1],
						['subtypeid'=>3009,'subtypename'=>'带手头像','displaycount'=>2],
						['subtypeid'=>3002,'subtypename'=>'组合几何','displaycount'=>1],
						['subtypeid'=>3015,'subtypename'=>'风景建筑','displaycount'=>2],
						['subtypeid'=>119,'subtypename' => "动物",'displaycount'=>1],
				];
				break;			
		}
		return $ret;
	}
	
	/**
	 * 根据类型取得获取素材首页建立缓存时，每个子项需要获取的记录条数，
	 * 缓存总数为1000，但是有的项不能保证100%有数据，所以sql的limit值要动态调配
	 * @param unknown $maintypeid
	 * @return Ambigous <NULL, multitype:multitype:number string  >
	 */
	static function getSelectCountByMainType($maintypeid){
		$ret = 0;
		switch ($maintypeid){
			//素描
			case 4:
				$ret=200;
				break;
				//色彩
			case 1:
				$ret=200;
				break;
				//速写
			case 5:
				$ret=200;
				break;
				//设计
			case 2:
				$ret=200;
				break;
				//创作
			case 6:
				$ret=500;
				break;
				//照片
			case 3:
				$ret=200;
				break;
		}
		return $ret;
	}
}
