<?php
namespace common\service;

use Yii;
use yii\base\Object;
use common\service\TagGroupService;
use common\service\TagsService;

/**
 * 字典数据相关逻辑
 * 包括：精讲分类字典数据
 *     课程分类字典数据
 * 
 */
class DictdataService extends Object
{    
	/**
	 * 获取全部精讲主类型
	 * @return multitype:number string
	 */
	static function getLectureMainType(){
		$ret = [
				['maintypeid'=>7,'maintypename'=>'联考'],
                ['maintypeid'=>4,'maintypename'=>'素描'],
				['maintypeid'=>1,'maintypename'=>'色彩'],
				['maintypeid'=>5,'maintypename'=>'速写'],
				['maintypeid'=>2,'maintypename'=>'设计'],
				#['maintypeid'=>6,'maintypename'=>'创作'],
				#['maintypeid'=>3,'maintypename'=>'照片'],
				['maintypeid'=>8,'maintypename'=>'校考'],				
				['maintypeid'=>9,'maintypename'=>'趣味'],				
				['maintypeid'=>10,'maintypename'=>'心理'],				
		];
		return $ret;
	}
	
	/**
	 * 根据精讲主类型获取对应数组
	 * @param unknown $maintypeid
	 * @return Ambigous <multitype:number , multitype:multitype:number string  >|boolean
	 */
	static  function getLectureMainTypeById($maintypeid){
		$ret = self::getLectureMainType();		
		foreach($ret as $k=>$v){
			if($v['maintypeid']==$maintypeid){
				return $v;
			}
		}
		return false;
	}
	
	/**
	 * 根据精讲主类型获取对应全部分类型
	 * @param unknown $maintypeid
	 */
	static function getLectureSubType($maintypeid){
		$ret = null;
		switch ($maintypeid){
			//素描			
			case 4:
				$ret=[
					['subtypeid'=>123,'subtypename'=>'单体几何'],
					['subtypeid'=>125,'subtypename'=>'单体静物'],
					['subtypeid'=>124,'subtypename'=>'组合几何'],
					['subtypeid'=>126,'subtypename'=>'组合静物'],
					['subtypeid'=>127,'subtypename'=>'石膏五官'],
					['subtypeid'=>128,'subtypename'=>'石膏像'],
					['subtypeid'=>129,'subtypename'=>'头像'],
					['subtypeid'=>130,'subtypename'=>'半身像'],
					['subtypeid'=>131,'subtypename'=>'人体解剖'],
					//增加
					['subtypeid'=>4004,'subtypename'=>'场景'],
					['subtypeid'=>4005,'subtypename'=>'风景建筑'],
					['subtypeid'=>4006,'subtypename'=>'动物'],
					['subtypeid'=>4003,'subtypename'=>'全身像'],
					['subtypeid'=>4002,'subtypename'=>'人物局部'],
					['subtypeid'=>4001,'subtypename'=>'石膏解剖'],
					//删除
					//['subtypeid'=>132,'subtypename'=>'大师作品']
				];
				break;
			//色彩
			case 1:
				$ret=[
						['subtypeid'=>100,'subtypename'=>'组合静物'], //原名 静物
						['subtypeid'=>101,'subtypename'=>'单体静物'],//原名 静物单体
						['subtypeid'=>102,'subtypename'=>'头像'],
						['subtypeid'=>103,'subtypename'=>'风景'],

						['subtypeid'=>'1001','subtypename'=>'半身像'],//增加
						['subtypeid'=>'1002','subtypename'=>'全身像'],//增加
						['subtypeid'=>'1003','subtypename'=>'场景'],//增加
						/*['subtypeid'=>104,'subtypename'=>'大师作品'],
						['subtypeid'=>105,'subtypename'=>'小色稿'],
						['subtypeid'=>106,'subtypename'=>'单色塑造']*/
				];
				break;
			//速写
			case 5:
				$ret=[
						['subtypeid'=>133,'subtypename'=>'人物速写'],
						['subtypeid'=>134,'subtypename'=>'人物局部速写'],
						['subtypeid'=>135,'subtypename'=>'动态快写'],
						['subtypeid'=>136,'subtypename'=>'人体结构'],
						['subtypeid'=>137,'subtypename'=>'场景速写'],
						['subtypeid'=>138,'subtypename'=>'命题速写'],

						//增加
						['subtypeid'=>5002,'subtypename'=>'风景速写'],
						['subtypeid'=>5003,'subtypename'=>'动物'],
						['subtypeid'=>5004,'subtypename'=>'道具'],
						['subtypeid'=>5001,'subtypename'=>'人物半身速写'],
						//删除
						//['subtypeid'=>139,'subtypename'=>'大师作品']
				];
				break;
			//设计
			case 2:
				$ret=[
						['subtypeid'=>107,'subtypename'=>'单体装饰画'],
						['subtypeid'=>109,'subtypename'=>'单体创意速写'],
						
						['subtypeid'=>110,'subtypename'=>'命题创意速写'], //主题创意速写
						['subtypeid'=>111,'subtypename'=>'字体设计'],
						['subtypeid'=>112,'subtypename'=>'设计素描'],
						['subtypeid'=>113,'subtypename'=>'设计色彩'],
						['subtypeid'=>114,'subtypename'=>'平面设计'],
						['subtypeid'=>115,'subtypename'=>'立体构成'],
						//增加
						['subtypeid'=>2003,'subtypename'=>"黑白装饰画"],
						['subtypeid'=>2002,'subtypename'=>"彩色装饰画"],
						['subtypeid'=>2001,'subtypename'=>"设计基础"],
						//删除
						//['subtypeid'=>108,'subtypename'=>'主题装饰画'],
				];
				break;
			//创作
			case 6:
				$ret=[
						//增加
						['subtypeid'=>6001,'subtypename'=>'室内'],
						['subtypeid'=>6002,'subtypename'=>'室外']
						/*删除
						['subtypeid'=>140,'subtypename'=>'材料'],
						['subtypeid'=>141,'subtypename'=>'颜色'],
						['subtypeid'=>142,'subtypename'=>'场景'],
						['subtypeid'=>143,'subtypename'=>'天气'],
						['subtypeid'=>144,'subtypename'=>'时间'],
						['subtypeid'=>145,'subtypename'=>'节日']*/
				];
				break;
			//照片
			case 3:
				$ret=[
						//增加
						['subtypeid'=>3001,'subtypename'=>'单体几何'],
						['subtypeid'=>3002,'subtypename'=>'组合几何'],
						['subtypeid'=>3003,'subtypename'=>'单体静物'],
						['subtypeid'=>3004,'subtypename'=>'组合静物'],
						['subtypeid'=>3005,'subtypename'=>'石膏五官'],
						['subtypeid'=>3006,'subtypename'=>'石膏解剖'],
						['subtypeid'=>3007,'subtypename'=>'石膏人像'],
						['subtypeid'=>3008,'subtypename'=>'石膏人物局部'],
						['subtypeid'=>3009,'subtypename'=>'带手头像'],
						['subtypeid'=>3010,'subtypename'=>'头像'],
						['subtypeid'=>3011,'subtypename'=>'半身像'],
						['subtypeid'=>3012,'subtypename'=>'全身像'],
						['subtypeid'=>3013,'subtypename'=>'道具'],
						['subtypeid'=>3014,'subtypename'=>'人物场景'],
						['subtypeid'=>3015,'subtypename'=>'风景建筑'],


						['subtypeid'=>119,'subtypename' => "动物"],
						['subtypeid'=>117,'subtypename' => "场景"],
						//删除
						//['subtypeid'=>120,'subtypename'=>'天气'],
						//['subtypeid'=>121,'subtypename'=>'时间'],
						//['subtypeid'=>122,'subtypename'=>'节日']
						//['subtypeid'=>118,'subtypename'=>'人物'],
						////['subtypeid'=>116,'subtypename'=>'静物'],
				];
				break;			
		}
		return $ret;
	}
	/**
	 * 获取单个分类型
	 * @param unknown $mainTypeid
	 * @param unknown $subTypeid
	 */
	static function getLectureSubTypeById($mainTypeid,$subTypeid){
		$ret = self::getLectureSubType($mainTypeid);
		foreach($ret as $k=>$v){
			if($v['subtypeid']==$subTypeid){
				return $v;
			}
		}
		return false;		
	}
	
	
	//*******************************************************lesson begin
	/**
	 * 获取全部跟着画主类型
	 * @return multitype:number string
	 */
	static function getLessonMainType(){
		$ret = [['maintypeid'=>4,'maintypename'=>'素描'],
				['maintypeid'=>1,'maintypename'=>'色彩'],
				['maintypeid'=>5,'maintypename'=>'速写'],
				['maintypeid'=>2,'maintypename'=>'设计'],
				//['maintypeid'=>7,'maintypename'=>'优秀试卷'],
		];
		return $ret;
	}
	
	/**
	 * 根据考点主类型获取对应数组
	 * @param unknown $maintypeid
	 * @return Ambigous <multitype:number , multitype:multitype:number string  >|boolean
	 */
	static  function getLessonMainTypeById($maintypeid){
		$ret = self::getLessonMainType();
		foreach($ret as $k=>$v){
			if($v['maintypeid']==$maintypeid){
				return $v;
			}
		}
		return false;
	}
	
	/**
	 * 根据精讲主类型获取对应全部分类型
	 * @param unknown $maintypeid
	 */ 
	static function getLessonSubType($maintypeid){
		/*色彩 小色稿 改成 组合静物*/
		$ret = null;
		switch ($maintypeid){
			//素描
			case 4:
				$ret=[
					['subtypeid'=>125,'subtypename'=>'单体静物'],
					['subtypeid'=>126,'subtypename'=>'组合静物'],
					['subtypeid'=>129,'subtypename'=>'头像'],
					//['subtypeid'=>123,'subtypename'=>'单体几何'],
					['subtypeid'=>124,'subtypename'=>'组合几何'],
					//['subtypeid'=>127,'subtypename'=>'石膏五官'],
					['subtypeid'=>128,'subtypename'=>'石膏像'],
					['subtypeid'=>130,'subtypename'=>'半身像'],
				];
				break;
			//设计
			case 2:
				$ret= [	//'108' => "命题装饰画",//主题装饰画
						//['subtypeid'=>2001,'subtypename'=>"设计基础"],
						//['subtypeid'=>107,'subtypename' => "单体装饰画"],
						['subtypeid'=>2003,'subtypename'=>"黑白装饰画"],
						['subtypeid'=>2002,'subtypename'=>"彩色装饰画"],
						//['subtypeid'=>109,'subtypename' => "单体创意速写"],
						 ['subtypeid'=>110,'subtypename' => "命题创意速写"],//主题创意速写
						//['subtypeid'=>111,'subtypename' => "字体设计"],
						['subtypeid'=>112,'subtypename' => "设计素描"],
						['subtypeid'=>113,'subtypename' => "设计色彩"],
						['subtypeid'=>114,'subtypename' => "平面设计"],
						//['subtypeid'=>115,'subtypename' => "立体构成"]
					];
				break;
				
			//色彩				
			case 1:
				$ret=[
					['subtypeid'=>100,'subtypename'=>'组合静物'], //静物
					['subtypeid'=>101,'subtypename'=>'单体静物'], //静物单体
					
					//删除
					//['subtypeid'=>105,'subtypename'=>'小色稿'],
				];
				break;
			//速写
			case 5:
				$ret=[
					['subtypeid'=>133,'subtypename'=>'人物速写'],
					['subtypeid'=>134,'subtypename'=>'人物局部速写'],
					['subtypeid'=>138,'subtypename'=>'命题速写'],
					['subtypeid'=>5004,'subtypename'=>'场景道具'],
					['subtypeid'=>135,'subtypename'=>'动态快写'],					
				];
				break;
			//优秀试卷
			case 7:
					$ret=[
					['subtypeid'=>1,'subtypename'=>'清华大学美术学院'],
					['subtypeid'=>2,'subtypename'=>'中央美术学院'],
					['subtypeid'=>3,'subtypename'=>'中国美术学院'],
					['subtypeid'=>4,'subtypename'=>'鲁迅美术学院'],
					['subtypeid'=>5,'subtypename'=>'四川美术学院'],
					['subtypeid'=>6,'subtypename'=>'天津美术学院'],
					['subtypeid'=>7,'subtypename'=>'广州美术学院'],
					['subtypeid'=>8,'subtypename'=>'西安美术学院'],
					['subtypeid'=>9,'subtypename'=>'湖北美术学院']
					];
					break;
		}
		return $ret;
	}
	/**
	 * 获取单个分类型
	 * @param unknown $mainTypeid
	 * @param unknown $subTypeid
	 */
	static function getLessonSubTypeById($mainTypeid,$subTypeid){
		$ret = self::getLessonSubType($mainTypeid);
		foreach($ret as $k=>$v){
			if($v['subtypeid']==$subTypeid){
				return $v;
			}
		}
		return false;
	}
	
	/**
	 * 获取推荐跟着画类型
	 * @return multitype:number string
	 */
	static function getLessonDisplayCountType(){
		$ret = [
					[
						'maintype' => "4",'name' => "素描",
						'subtype' => [
							['subtype' => "129",'name' => "头像",'displaycount'=>2],
							['subtype' => "126",'name' => "组合静物",'displaycount'=>2],
						]
					],
					[
						'maintype' => "1",'name' => "色彩",
						'subtype' => [
							['subtype' => "100",'name' => "组合静物", 'displaycount'=>3],
							['subtype' => "101",'name'  => "静物单体",'displaycount'=>2],
						],
					],
					[
						'maintype' => "5",'name' => "速写",
						'subtype' => [
							['subtype' => "133",'name' => "人物速写",'displaycount'=>3],
							['subtype' => "134",'name'  => "人物局部速写",'displaycount'=>1],
							['subtype' => "138",'name' => "命题速写",'displaycount'=>1],
						] 
					],
					[
						'maintype' => "4",'name' => "素描",
						'subtype' => [
							['subtype' => "128",'name'  => "石膏像",'displaycount'=>1],
							['subtype' => "124",'name' => "组合几何",'displaycount'=>1],
						]
					],
					[
						'maintype' => "5",'name' => "速写",
						'subtype' => [
							['subtype' => "135",'name' => "动态快写",'displaycount'=>1],
							['subtype' => '5004','name'=>'道具','displaycount'=>1],
						] 
					],
					[
						'maintype' => "2",'name' => "设计",
						'subtype' => [
								['subtype' => "2003",'name'=>"黑白装饰画",'displaycount'=>1],
								['subtype' => "110",'name' => "命题创意速写",'displaycount'=>1],
								['subtype' => "112",'name' => "设计素描",'displaycount'=>1],
								['subtype' => "113",'name' => "设计色彩",'displaycount'=>1],
						],
					]
                ];
		return $ret;

	}
	
	/**
	 * 根据类型取得获取跟住画首页建立缓存时，每个子项需要获取的记录条数，
	 * 缓存总数为1000，但是有的项不能保证100%有数据，所以sql的limit值要动态调配
	 * @param unknown $maintypeid
	 * @return Ambigous <NULL, multitype:multitype:number string  >
	 */
	static function getLessonSelectCountByMainType($maintypeid){
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
		}
		return $ret;
	}


	
	
	//*******************************************************lesson end
	
	//*******************************************************posidhome begin
	/**
	 * 获取首页推荐位的全部类型
	 * @return multitype:number string
	 */
	static function getPosidHomeType(){
		$ret = [['typeid'=>1,'typename'=>'html页'],
				['typeid'=>2,'typename'=>'考点'],
				['typeid'=>3,'typename'=>'活动'],
				['typeid'=>4,'typename'=>'精讲'],
				['typeid'=>5,'typename'=>'个人主页'],
				['typeid'=>6,'typename'=>'专题'],
				['typeid'=>7,'typename'=>'直播'],
				['typeid'=>8,'typename'=>'课程']		
		];
		return $ret;
	}
	
	/**
	 * 根据考点主类型获取对应数组
	 * @param unknown $maintypeid
	 * @return Ambigous <multitype:number , multitype:multitype:number string  >|boolean
	 */
	static  function getPosidHomeTypeById($typeid){
		$ret = self::getPosidHomeType();
		foreach($ret as $k=>$v){
			if($v['typeid']==$typeid){
				return $v;
			}
		}
		return false;
	}
	//*******************************************************lesson end
	
	
	//*******************************************************tweet type begin
	/**
	 * 获取全部帖子主类型
	 * @return multitype:number string
	 */
	static function getTweetMainType(){
		$ret = ['1' => "色彩",
				'2' => "设计",
				'3' => "照片",
				'4' => "素描",
				'5' => "速写",
				'6' => "创作"];
		return $ret;
	}
	
	/**
	 * 根据帖子一级分类的名称获取id
	 * @return multitype:string
	 */
	static function getTweetMainTypeIdByName($name){
		$mainmodels = static::getTweetMainType();
		foreach ($mainmodels as $k=>$v){
			if($v == $name){
				return $k;
			}
		}
		return null;
	}
	/**
	 * 根据主类型id获取名称
	 * @param unknown $maintypeid
	 * @return Ambigous <multitype:number , multitype:multitype:number string  >|boolean
	 */
	static  function getTweetMainTypeById($maintypeid){
		$ret = self::getTweetMainType();		
		foreach($ret as $k=>$v){
			if($k==$maintypeid){
				return $v;
			}
		}
		return false;
	}

	/**
	 * 获取全部帖子子类型
	 * @return multitype:number string
	 */
	static function getTweetSubType(){
		$ret = [//色彩
				'1' => [
						'101' => "单体静物",//静物单体
						'100' => "组合静物", //静物
						'1003'=>'场景',//增加
						'102' => "头像",
						'1001'=>'半身像',//增加
						'1002'=>'全身像',//增加
						'103' => "风景",
						//删除
						//'104' => "大师作品",
						//'105' => "小色稿",
						//'106' => "单色塑造"
						],
				//设计
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
				//照片
				'3' => [
						'3001'=>'单体几何',
						'3002'=>'组合几何',
						'3003'=>'单体静物',
						'3004'=>'组合静物',
						'3005'=>'石膏五官',
						'3006'=>'石膏解剖',
						'3007'=>'石膏人像',
						'3008'=>'石膏人物局部',
						'3009'=>'带手头像',
						'3010'=>'头像',
						'3011'=>'半身像',
						'3012'=>'全身像',
						'3013'=>'道具',
						'117' => "场景",
						'3014'=>'人物场景',
						'3015'=>'风景建筑',
						'119' => "动物"
						//删除
						//'116' => "静物",
						//'118' => "人物",
						//'120' => "天气",
						//'121' => "时间",
						//'122' => "节日"
						],
				//素描
				'4' => ['123' => "单体几何",
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
				//速写
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
						],
				//创作
				'6' => [
						'6001'=>'室内',
						'6002'=>'室外'
						//删除
						//'140' => "材料",
						//'141' => "颜色",
						//'142' => "场景",
						//'143' => "天气",
						//'144' => "时间",
						//'145' => "节日"
						]                  
                ];
		return $ret;
	}
	
	/**
	 * 根据帖子二级分类的名称取id
	 * @param unknown $maintypeid
	 * @param unknown $name
	 * @return Ambigous <multitype:number , multitype:string >|NULL
	 */
	static function getTweetSubTypeIdByName($maintypeid,$name){
		$allmodels = static::getTweetSubType();
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
	/**
	 * 获取帖子单个分类型
	 * @param unknown $mainTypeid
	 * @param unknown $subTypeid
	 */
	static function getTweetSubTypeById($mainTypeid,$subTypeid){
		$allmodels = static::getTweetSubType();
		$submodels = $allmodels[$mainTypeid];

		if(!$submodels){
			return null;
		} 
		foreach($submodels as $k=>$v){
			if($k==$subTypeid){
				return $v;
			}
		}
		return false;		
	}
	/**
	 * 获取帖子分类和标签（缓存）
	 * type: default/catalog(获取帖子分类键值名称不同)
	 */
	public static function getTweetTypeAndTagStr($type="default"){
	    $rediskey="tweet_tags_json_".$type;
	    $redis = Yii::$app->cache;
	    // $redis->delete($rediskey);
	    $typetags=$redis->get($rediskey);
	    if (empty($typetags)) {
	       $typetags=self::getTweetTypeAndTagStrDb($type);
	       if($typetags){
	            $redis->set($rediskey,$typetags);
	            $redis->expire($rediskey,3600*24*3);
	       }
	    }
	    return $typetags;
	}

	/**
	 * 数据库获取帖子分类和标签 （替换原json配置文件）数据库获取
	 * @return [type] [description]
	 */
	public static function getTweetTypeAndTagStrDb($type="default"){
		$idkeyname="subid";
		//处理帖子分类获取返回结构不同
		switch ($type) {
			case 'catalog':
				$idkeyname="id";
				break;
		}
		$data=[];
		$maintype=self::getTweetMainType();
		foreach ($maintype as $main_key => $main_value) {
			$maintype_item[$idkeyname]=$main_key;
			$maintype_item['name']=$main_value;
			$subtype=self::getTweetSubType()[$main_key];
			foreach ($subtype as $sub_key => $sub_value) {
				$subtype_item[$idkeyname]=$sub_key;
				$subtype_item['name']=$sub_value;
				$taggroup=TagGroupService::getTagGroupByType($main_key,$sub_key);
				$subtype_item['tag_group']=[];
				foreach ($taggroup as $group_key => $group_value) {
						$tagroup_item['name']=$group_value['tag_group_name'];
						$tagroup_item['tag_group_type']=$group_value['tag_group_type'];
						$tagroup_item['tag']=TagsService::getTagsByGroupId($group_value['taggroupid']);
						$subtype_item['tag_group'][]=$tagroup_item;
						unset($tagroup_item);
				}
				$maintype_item['catalog'][]=$subtype_item;
				unset($subtype_item);
			}
			$data[]=$maintype_item;
			unset($maintype_item);
		}
		$return_arr['data']=$data;
		$return_json=json_encode($return_arr);
		return $return_json;
	}



	/**
	 * 数据库获取帖子分类
	 * @return [type] [description]
	 */
	public static function getTweetType(){
		$idkeyname="id";
		$data=[];
		$maintype=self::getTweetMainType();
		foreach ($maintype as $main_key => $main_value) {
			$maintype_item[$idkeyname]=$main_key;
			$maintype_item['name']=$main_value;
			$subtype=self::getTweetSubType()[$main_key];
			foreach ($subtype as $sub_key => $sub_value) {
				$subtype_item[$idkeyname]=$sub_key;
				$subtype_item['name']=$sub_value;
				$maintype_item['catalog'][]=$subtype_item;
				unset($subtype_item);
			}
			$data[]=$maintype_item;
			unset($maintype_item);
		}
		
		return $data;
	}

	/**
	 * 获取帖子的主类型 分类型 tag信息
	 */
	static function getTweetTypeAndTag(){
		$tag_json=json_decode(self::getTweetTypeAndTagStr(),true);
		return $tag_json;
	}
	
	/**
	 * 获取求批改的主类型和分类型
	 * @return mixed
	 */
	static function getCorrectTypeAndTag(){
		$tag_json=[
		    "data"=> [
		        [
		            "catalog"=> [
		                ["subid"=>"123","name"=>"单体几何"], 
		                ["subid"=>"124","name"=>"组合几何"],
		                ["subid"=>"125","name"=>"单体静物"],
		                ["subid"=>"126","name"=>"组合静物"],
		                ["subid"=>"127","name"=>"石膏五官"],
		                ["subid"=>"128","name"=>"石膏像"],		            		
	            		["subid"=>"4002","name"=>"人物局部"],
		                ["subid"=>"129","name"=>"头像"],
		                ["subid"=>"130","name"=>"半身像"],
		            	["subid"=>"4003","name"=>"全身像"],
	            		["subid"=>"4005","name"=>"风景建筑"],
	            		["subid"=>"4006","name"=>"动物"]
		            ],
		            "subid"=>"4",
		            "name"=>"素描"
		        ],
		        [
		            "catalog"=>[
		                ["subid"=>"133","name"=>"人物速写"],
	            		["subid"=>"134","name"=>"人物局部速写"],		            	
	            		["subid"=>"137","name"=>"场景速写"],	            		
	            		["subid"=>"138","name"=>"命题速写"],
		            	["subid"=>"5002","name"=>"风景速写"],
		            	["subid"=>"5003","name"=>"动物"]   
		            ],
		            "subid"=>"5",
		            "name"=>"速写"
		        ],
		        [
		            "catalog"=>[
	            		["subid"=>"101","name"=>"单体静物"],
		                ["subid"=>"100","name"=>"组合静物"],		            		
	            		["subid"=>"1003","name"=>"场景"],		            		
		                ["subid"=>"102","name"=>"头像"],   
	            		["subid"=>"1001","name"=>"半身像"],
	            		["subid"=>"1002","name"=>"全身像"],
		                ["subid"=>"103","name"=>"风景"]
		            ],
		            "subid"=>"1",
		            "name"=>"色彩"
		        ],
		        [
		            "catalog"=>[
		                ["subid"=>"112","name"=>"设计素描"],
		                ["subid"=>"113","name"=>"设计色彩"],
		                ["subid"=>"114","name"=>"平面设计"],
		                ["subid"=>"110","name"=>"命题创意速写"],
		                ["subid"=>"2003","name"=>"黑白装饰画"],
		                ["subid"=>"2002","name"=>"彩色装饰画"]
		            ],
		            "subid"=>"2",
		            "name"=>"设计"
		        ]
		    ]
		];
		return $tag_json;
	}
	
	/**
	 * 根据帖子二级分类型id取出对应的标签数组
	 * @param unknown $subtypeid
	 */
	static function getTweetSubTypeTags($subtypeid)
	{
		$tag_json=json_decode(self::getTweetTypeAndTagStr());
		$data = $tag_json->data;
		foreach ($data as $key => $value) {
			foreach ($value->catalog as $key1 => $value1) {
				if( $subtypeid ==  $value1->subid){
					$tags=$value1->tag_group;
					return $tags; 
				}
			}
		}
		return null;
	}
	//*******************************************************tweet type end

 	/**
     * 用户级别
     */
    static function getUserGrade(){
    	return  array(
    		array('gradeid'=>1,'gradename'=>'Lv1','scoin'=>0,'ecoin'=>50),
	    	array('gradeid'=>2,'gradename'=>'Lv2','scoin'=>51,'ecoin'=>150),
	    	array('gradeid'=>3,'gradename'=>'Lv3','scoin'=>151,'ecoin'=>350),
	    	array('gradeid'=>4,'gradename'=>'Lv4','scoin'=>351,'ecoin'=>600),
	    	array('gradeid'=>5,'gradename'=>'Lv5','scoin'=>601,'ecoin'=>1000),
	    	array('gradeid'=>6,'gradename'=>'Lv6','scoin'=>1001,'ecoin'=>1800),
	    	array('gradeid'=>7,'gradename'=>'Lv7','scoin'=>1801,'ecoin'=>3000),
	    	array('gradeid'=>8,'gradename'=>'Lv8','scoin'=>3001,'ecoin'=>5000),
	    	array('gradeid'=>9,'gradename'=>'Lv9','scoin'=>5001,'ecoin'=>10000),
	    	array('gradeid'=>10,'gradename'=>'Lv10','scoin'=>10001,'ecoin'=>18000),
	    	array('gradeid'=>11,'gradename'=>'Lv11','scoin'=>18001,'ecoin'=>30000),
	    	array('gradeid'=>12,'gradename'=>'Lv12','scoin'=>30001,'ecoin'=>45000),
    	);
    }

    /**
     * 根据id获取省
     * @param  [type] $provinceid [description]
     * @return [type]             [description]
     */
    static function getUserProvinceById($provinceid){
    	if($provinceid){
    		$province=self::getProvince();
    		foreach ($province as $key => $value) {
    			if($value['provinceid']==$provinceid){
    				return $value['provincename'];
    			}
    		}
    	}    	
    	return false;
    }

    /**
     * 根据id获取省
     * @param  [type] $provinceid [description]
     * @return [type]             [description]
     */
    static function getUserProvinceByName($provincename){
    	if($provincename){
    		$province=self::getProvince();
    		foreach ($province as $key => $value) {
    			if($value['provincename']==$provincename){
    				return $value['provinceid'];
    			}
    		}
    	}    	
    	return false;
    }
     /**
     * 初始化省
     */
    static function getProvince(){
    	/*4个直辖市: 北京市,上海市,天津市,重庆市
		5个自治区:内蒙古自治区,宁夏回族自治区,新疆维吾尔自治区,西藏自治区,广西壮族自治区
		2个行政区:香港特别行政区,澳门特别行政区
		23个省：黑龙江省、辽宁省、吉林省、河北省、河南省、湖北省、湖南省、山东省、山西省、陕西省、
		安徽省、浙江省、江苏省、福建省、广东省、海南省、四川省、云南省、贵州省、青海省、
		甘肃省、江西省、台湾省*/
    	return array(
	    	array('provinceid'=>1,'provincename'=>'北京市'),	
	    	array('provinceid'=>2,'provincename'=>'山东省'),
	    	array('provinceid'=>3,'provincename'=>'广东省'),
	    	array('provinceid'=>4,'provincename'=>'河南省'),
	    	array('provinceid'=>5,'provincename'=>'重庆市'),
	    	array('provinceid'=>6,'provincename'=>'辽宁省'),
	    	array('provinceid'=>7,'provincename'=>'吉林省'),
	    	array('provinceid'=>8,'provincename'=>'黑龙江省'),
	    	array('provinceid'=>9,'provincename'=>'内蒙古自治区'),
	    	array('provinceid'=>10,'provincename'=>'四川省'),
	    	array('provinceid'=>11,'provincename'=>'湖南省'),
	    	array('provinceid'=>12,'provincename'=>'湖北省'),
	    	array('provinceid'=>13,'provincename'=>'天津市'),
	    	array('provinceid'=>14,'provincename'=>'河北省'),
	    	array('provinceid'=>15,'provincename'=>'山西省'),
	    	array('provinceid'=>16,'provincename'=>'上海市'),
	    	array('provinceid'=>17,'provincename'=>'江苏省'),
	    	array('provinceid'=>18,'provincename'=>'浙江省'),
	    	array('provinceid'=>19,'provincename'=>'安徽省'),
	    	array('provinceid'=>20,'provincename'=>'福建省'),
	    	array('provinceid'=>21,'provincename'=>'江西省'),
	    	array('provinceid'=>22,'provincename'=>'广西壮族自治区'),
	    	array('provinceid'=>23,'provincename'=>'海南省'),
	    	array('provinceid'=>24,'provincename'=>'贵州省'),
	    	array('provinceid'=>25,'provincename'=>'云南省'),
	    	array('provinceid'=>26,'provincename'=>'西藏自治区'),
	    	array('provinceid'=>27,'provincename'=>'陕西省'),
	    	array('provinceid'=>28,'provincename'=>'甘肃省'),
	    	array('provinceid'=>29,'provincename'=>'青海省'),
	    	array('provinceid'=>30,'provincename'=>'宁夏回族自治区'),
	    	array('provinceid'=>31,'provincename'=>'新疆维吾尔自治区'),
	    	array('provinceid'=>32,'provincename'=>'香港特别行政区'),
	    	array('provinceid'=>33,'provincename'=>'澳门特别行政区'),
	    	array('provinceid'=>34,'provincename'=>'台湾省'),
	    	array('provinceid'=>35,'provincename'=>'其他国家'),
    	);
    } 
    
    static function getGenderByid($genderid){
    	$gender=self::getGender();
    	foreach ($gender as $key => $value) {
    		if($genderid==$value['genderid']){
    			return $value['gendername'];
    		}
    	}
    	return null;
    }
    /**
     * 初始化性别
     */
    static function getGender(){
    	return array(
    		array('genderid'=>0,'gendername'=>'男'),
    		array('genderid'=>1,'gendername'=>'女'),
    	);
    }
    /**
     * 根据身份id 获取身份信息
     * @param  [type] $professionid [description]
     * @return [type]               [description]
     */
    static function getProfessionById($professionid){
    	 if($professionid==0 || $professionid){
    		$profession=self::getProfession();
    		foreach ($profession as $key => $value) {
    			if($professionid==$value['professionid']){
    				return $value['professionname'];
    			}
    		}
    	}
    	return null;
    }
    /**
     * 初始化身份
     */
    static function getProfession(){
    	return  array(
    		array('professionid'=>0,'professionname'=>'高三'),
	    	array('professionid'=>1,'professionname'=>'高二'),
	    	array('professionid'=>2,'professionname'=>'高一'),
	    	array('professionid'=>3,'professionname'=>'初中'),
	    	array('professionid'=>4,'professionname'=>'大学'),
    		array('professionid'=>5,'professionname'=>'老师'),
	    	array('professionid'=>6,'professionname'=>'其他'),
	    	array('professionid'=>7,'professionname'=>'小学'),
    	);
    }


    //*******************************************************tweet type begin
	/**
	 * 获取马甲用户
	 * @return multitype:number string
	 */
	static function getVestUser(){
	 	$ret=[];
	 	for ($i=500; $i <1000 ; $i++) { 
	 	 	$ret[]=$i;
	 	}
		return $ret;
	}
	/**
	 * 获取mis后台顶部菜单显示权限
	 * @return [type] [description]
	 */
	static function getMisTopMenu(){
		/*  
        1 	管理员
        2 	运营
        3 	运营素材管理
        4 	运营删帖
        5 	运营删评论
        6 	运营推送        
        7 	运营黑名单管理
        8 	运营老师管理        
        9 	运营统计
        10	产品
        11	精讲评论员
        12	活动运营管理
		13	运营启动页管理
		14	正能后台文章管理
		17	批改管理
		18	素材专题管理
        19	联考打分 
        20	出版社管理 
        21	工具管理 => 渠道包管理 
        23	能力模型素材管理
        24	视频管理
        25	课程管理
        27	画室管理 => 画室管理 
        28	跟着画管理员 => 跟着画管理员 
        29	标签管理 => 标签管理 
        */
        //菜单对应的用户角色id
		$ret = [
                //首页
                // '0'=>[1,2,3,4,5,6,7,8,9,10,11],
                //运营
                '1'=>[1,2,3,4,5,6,7,8,9,10,11,12,13,15,17,18,19,20,21,22,23,24,25,26,27,29,30],
                //正能后台
                '2'=>[1,14],
                //管理员
                '3'=>[1],
                //个人信息
                //'4'=>[1,2,3,4,5,6,7,8,9,10,11],
                ];
        return $ret;
	}

	/**
	 * ios 购买产品 价格 id（用于苹果支付）
	 * @return [type] [description]
	 */
	public static function getIosProductPriceId(){
		$ret=  [
					['price'=>1], 
					['price'=>3], 
					['price'=>6],
					['price'=>8],
					['price'=>18],
					['price'=>28],
					['price'=>40],
					['price'=>60],
					['price'=>98],
					['price'=>108],
					['price'=>198],
					['price'=>298],
					['price'=>388],
					['price'=>488],
					['price'=>588],
					['price'=>998],
					['price'=>1298],
					['price'=>1598],
					['price'=>1998],
					['price'=>2298],
					['price'=>3998],
					['price'=>4998],
					['price'=>5898],
					['price'=>6498],
			    ];
		foreach ($ret as $key => $value) {
			//添加productid
			$ret[$key]['productid']="myb_in_app_purchase_".$value['price'];
		}
		return $ret;
	}

	/**
	 * 通过价格获取ios苹果支付产品id
	 */
	public static function getIosProductidByPrice($price){
		$all=self::getIosProductPriceId();
		foreach ($all as $key => $value) {
			if($price==$value['price']){
				return $value['productid'];
			}
		}
		return null;
	}

}
