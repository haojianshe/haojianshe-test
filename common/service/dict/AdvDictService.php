<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 广告配置
 */
class AdvDictService extends Object
{    
	/**
	 * 获取全部详情类型 每个详情对应二级分类设置广告
	 * @return multitype:number string
	 */
	static function getDetailAdvType(){
		$ret = [
				'1' => "素材",
				'2' => "课程视频",
				'3' => "批改"
				];
		return $ret;
	}
	/**
	 * 列表banner位类型 每个分类都为6张图的轮播图
	 * @return [type] [description]
	 */
	static function getListAdvType(){
		$ret=[
				[
					'id'=>"1",
					'name'=>'学习页',
				],
				
				[
					'id'=>"4",
					'name'=>'首页',
					'catalog'=>[
						"109"=>"首页广告",
					]					
				],
				[
					'id'=>"2",
					'name'=>'改画',
					'catalog'=>[
						"105"=>"改画",
						"106"=>"作品"
					]			
				],
				[
					'id'=>"3",
					'name'=>'课程页',
					'catalog'=>[
						"107"=>"视频推荐",
						"108"=>"素材推荐"
					]					
				],
				[
					'id'=>"5",
					'name'=>'一招',
					'catalog'=>[
						"110"=>"最新",
					]	
				]
			];
			return $ret;
	}
}
