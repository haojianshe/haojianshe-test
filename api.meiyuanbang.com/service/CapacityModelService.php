<?php
namespace api\service;

use common\models\myb\CapacityModel;
use Yii;
use common\redis\Cache;
use common\service\dict\CapacityModelDictDataService;

/**
* 用户能力模型图
*/
class CapacityModelService extends CapacityModel
{  	
	/**
	 * 根据老师对求批改的       打分计算用户得分
	 * @param unknown $teacherScore 老师给打的分
	 * @param unknown $itemList 各项内容的权重信息
	 * @return unknown
	 */
	static function calScore($teacherScore,$itemlist) {
		$sumscore = 0;
		//计算总分
		foreach ($teacherScore as $k=>$v){
			$score = $v['score'];
			$itemid = $v['itemid'];
			$weight = CapacityModelDictDataService::getCorrectScoreItemWeightByItemid($itemid, $itemlist);
			$sumscore += $score*$weight;
		}
		//计算批改得分
		$itemcount = count($teacherScore);
		$ret = round($sumscore/100);
		return $ret;
	}
	
	/**
	 * 更新用户的能力模型总分和主类型 最新子类型等信息
	 * @param unknown $uid
	 * @param unknown $catalogid
	 * @param unknown $scatalogid
	 * @param unknown $teacherScore
	 * @param unknown $submittime 2.3.5新增，用于计算最近两周时间内5次求批改的平均分
	 * @return boolean
	 */
	static function updateTotalScore($uid,$catalogid,$scatalogid,$markDetails){
		$model = static::findOne(['uid'=>$uid,"catalogid"=>$catalogid]);
		if(!$model){
			$model = new CapacityModelService();
			$model->uid = $uid;
			$model->catalogid = $catalogid;
		}
		//2.3.5获取最近两周内，同类型的求批改数据
		$model->last_correct_scatalogid = $scatalogid;
		$model->marktimes = count($markDetails);
		//先清0
		$model->score1_totalmark = 0;
		$model->score2_totalmark = 0;
		$model->score3_totalmark = 0;
		$model->score4_totalmark = 0;
		$model->score5_totalmark = 0;
		$model->score6_totalmark = 0;
		$model->score7_totalmark = 0;
		//计算各项总分
		foreach ($markDetails as $k=>$v){
			$teacherScore = json_decode($v['markdetail'],true);
			//记录用户打分
			$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(1,$teacherScore)['score'];
			if($scoretmp){
				$model->score1_totalmark+=$scoretmp;
			}
			$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(2,$teacherScore)['score'];
			if($scoretmp){
				$model->score2_totalmark+=$scoretmp;
			}
			$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(3,$teacherScore)['score'];
			if($scoretmp){
				$model->score3_totalmark+=$scoretmp;
			}
			$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(4,$teacherScore)['score'];
			if($scoretmp){
				$model->score4_totalmark+=$scoretmp;
			}
			$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(5,$teacherScore)['score'];
			if($scoretmp){
				$model->score5_totalmark+=$scoretmp;
			}
			if($model->catalogid==1 || $model->catalogid==4){
				//色彩和素描都取7项
				$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(6,$teacherScore)['score'];
				if($scoretmp){
					$model->score6_totalmark+=$scoretmp;
				}
				$scoretmp = CapacityModelDictDataService::getCorrectScoreItemByItemid(7,$teacherScore)['score'];
				if($scoretmp){
					$model->score7_totalmark+=$scoretmp;
				}
			}
		}
		$ret = $model->save();
		if($ret){
			//清缓存
			$redis = Yii::$app->cache;
			$redis_key = 'userCapacityModel_' . $uid . '_' . $catalogid;
			$redis->delete($redis_key);
		}
		return $ret;
	}
	
	/**
	 * 根据类型获取用户的能力模型数据--暂时不用缓存
	 * @param unknown $uid
	 * @param number $catalogid 如果有值则取对应类型的模型
	 */
	static function getUserCapacityModel($uid,$catalogid){
		$redis = Yii::$app->cache;
		$redis_key = 'userCapacityModel_' . $uid . '_' . $catalogid;
		
		$ret = $redis->hgetall($redis_key);
		if($ret){
			//判断没有记录
			if(isset($ret['norecord']) && $ret['norecord']==1){
				return null;
			}
			return $ret;
		}
		//从数据库读取
		$model = parent::findOne(['uid'=>$uid,"catalogid"=>$catalogid]);
		if($model){
			$ret = $model->attributes;
			//存缓存,保留24小时
			$redis->hmset($redis_key, $ret);
			$redis->expire($redis_key,3600*24);
			return $ret;
		}
		else {
			$ret['norecord'] =1;
			//存缓存,保留24小时
			$redis->hmset($redis_key, $ret);
			$redis->expire($redis_key,3600*24);
			return null;
		}
	}
	
	/**
	 * 为model添加分数和打分项名称
	 * @param unknown $model
	 */
	static function addInfoToModel($model){
		$ret['catalogid'] = $model['catalogid'];
		$ret['catalogname'] = CapacityModelDictDataService::getCorrectMainTypeNameById($model['catalogid']);
		$ret['uid'] = $model['uid'];
		$ret['last_correct_scatalogid'] = $model['last_correct_scatalogid'];
		$mainid = $model['catalogid'];
		$times = $model['marktimes'];
		$items = CapacityModelDictDataService::getCorrectScoreItemByMainId($mainid);
		for ($i=1;$i<=count($items);$i++){
			$itemmodel = CapacityModelDictDataService::getCorrectScoreItemByItemid($i, $items);
			$tmp = [];
			$tmp['itemid']=$itemmodel['itemid'];
			$tmp['itemname']=$itemmodel['itemname'];
			$tmp['score']=round($model['score'.$i.'_totalmark']/$times);
			$ret['capacity'][] = $tmp;
		}
		return $ret;
	}
}