<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 课程字典数据
 */
class CourseDictDataService extends Object
{    
	
	/**
	 * 获取全部课程主类型
	 * @return multitype:number string
	 */
	static function getCourseMainType(){
		$ret = [		
				'102' => "联考状元笔记",		
				'4' => "素描",
				'5' => "速写",
				'1' => "色彩",
				'101' => "理论",
				'100' => "考试",
				
				];
				//暂时不使用设计
				//'2' => "设计"
		return $ret;
	}
	
	/**
	 * 根据课程一级分类的名称获取id
	 * @return multitype:string
	 */
	static function getCourseMainTypeIdByName($name){
		$mainmodels = static::getCourseMainType();
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
	static function getCourseMainTypeNameById($id){
		$mainmodels = static::getCourseMainType();
		foreach ($mainmodels as $k=>$v){
			if($k == $id){
				return $v;
			}
		}
		return null;
	}

	/**
	 * 获取全部课程类型
	 * @return multitype:number string
	 */
	static function getCourseSubType(){
		$ret = ['1' => [
						'101' => "静物单体",
						'100' => "组合静物", //静物
						'1003'=>'场景',
						'102' => "头像",
						'1001'=>'半身像',
						'1002'=>'全身像',
						'103' => "风景"
						//删除
						//'104' => "大师作品",
						//'105' => "小色稿",
						//'106' => "单色塑造"
						],
				'2' => [
						'2001'=>"设计基础",
						'107' => "单体装饰画",
						'2003'=>"黑白装饰画",
						'2002'=>"彩色装饰画",	
						//'108' => "命题装饰画",//主题装饰画
						'109' => "单体创意速写",
						'110' => "命题创意速写",//主题创意速写
						'111' => "字体设计",
						'112' => "设计素描",
						'113' => "设计色彩",
						'114' => "平面设计",
						'115' => "立体构成"
						],
				'4' => [
						'123' => "单体几何",
						'124' => "组合几何",
						'125' => "单体静物",
						'126' => "组合静物",
						'127' => "石膏五官",
						'4001'=>'石膏解剖',
						'128' => "石膏像",
						'4002'=>'人物局部',
						'129' => "头像",
						'130' => "半身像",
						'4003'=>'全身像',
						'131' => "人体解剖",
						'4004'=>'场景',
						'4005'=>'风景建筑',
						'4006'=>'动物'
						//删除
						//'132' => "大师作品"
						],
				'5' => [
						'133' => "人物速写",
						'5001'=>'人物半身速写',
						'134' => "人物局部速写",
						'135' => "动态快写",
						'136' => "人体结构",
						'137' => "场景速写",
						'138' => "命题速写",
						'5002'=>'风景速写',
						'5003'=>'动物',
						'5004'=>'道具'
						//删除
						//'139' => "大师作品"
						]  ,
				'100' => [
						'100001' => "央美附中"
						//'139' => "大师作品"
						],
				'101' => [
						'101001' => "色彩理论",
						'101002' => "造型理论",
						'101003' => "解剖理论",
						//'139' => "大师作品"
				],
				'102' => [
						'2'=>'山东省',
				    	'4'=>'河南省',
						'6'=>'辽宁省',
				    	'7'=>'吉林省',
						'8'=>'黑龙江省',
				    	'12'=>'湖北省',
				    	'14'=>'河北省',
				    	'20'=>'福建省',
						'24'=>'贵州省',
						'3'=>'广东省',
						'10'=>'四川省',
						'27'=>'陕西省',
						'9'=>'内蒙古自治区',
				]
                ];
		return $ret;
	}
	
	 /**
     * 获取课程二级分类
     * @param unknown $mainTypeid
     * @param unknown $subTypeid
     */
    static function getCourseSubTypeById($mainTypeid,$subTypeid){
        $ret = self::getCourseSubType($mainTypeid);
        $submodels = $ret[$mainTypeid];
        if(!$submodels){
            return null;
        } 
        foreach($submodels as $k=>$v){
            if($k==$subTypeid){
                return $v;
            }
        }
        return null;
    }

	/**
	 * 根据二级分类的名称取id
	 * @param unknown $maintypeid
	 * @param unknown $name
	 * @return Ambigous <multitype:number , multitype:string >|NULL
	 */
	static function getCourseSubTypeIdByName($maintypeid,$name){
		$allmodels = static::getCourseSubType();
		$submodels = $allmodels[$maintypeid];
		if(!$submodels){
			return null;
		}
		foreach ($submodels as $k=>$v){
			if($v == $name){
				return $k;
			}
		}
		return null;
	}
	
}
