<?php
namespace api\service;

use Yii;
use common\models\myb\Cointask;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;

/**
 * 
 * @author Administrator
 *
 */
class CointaskService extends Cointask 
{	
	/**
	 * 判断每日n此加金币的任务是否需要加金币
	 * @param unknown $uid
	 * @return boolean
	 */
	static function IsAddByDaily($uid,$tasktype){
		//当天时间戳
		$currentdate = strtotime(date("Y-m-d"));
		//获取用户当天加金币次数
		$model = static::findOne(['uid'=>$uid,'tasktype'=>$tasktype,'taskdate'=>$currentdate]);
		if(!$model){
			//从未加过金币则写记录
			$model = new CointaskService();
			$model->uid = $uid;
			$model->tasktype = $tasktype;
			$model->taskdate = $currentdate;
			$model->times =1;
			$model->ctime = time();
			if($model->save()){
				return true;
			}
			return false;
		}
		//判断当天评论加金币是否已到上限
		$maxtimes= CointaskDictService::getTaskTimes($tasktype);
		if($model->times >=$maxtimes){
			return false;
		}
		//加金币次数加1
		$model->times+=1;
		$model->save();
		return true;
	}
	
	/**
	 * 判断进入排行榜是否加分
	 * @param unknown $uid
	 * @param unknown $correctid
	 * @return boolean
	 */
	static function IsAddRank($uid,$correctid){	
		//取时间戳
		$currentdate = strtotime(date("Y-m-d"));
		$lastdate = $currentdate-24*3600;
		$tasktype = CointaskTypeEnum::RANK_LIST;
		
		//取最近两天的金币任务记录
		$models = (new \yii\db\Query())
		    	->select('*')
		    	->from(parent::tableName())
		    	->where(['uid'=>$uid])
		    	->andWhere(['tasktype'=>$tasktype])
		    	->andWhere(['>=','taskdate',$lastdate])
		    	->all();
		if($models && count($models)>0 ){
			foreach ($models as $k=>$v){
				$ids = explode(',', $v['dataremark']);
				if(in_array($correctid,$ids)){
					return false;
				}
			}
		}
		//可以加金币，则查找记录
		$model = static::findOne(['uid'=>$uid,'tasktype'=>$tasktype,'taskdate'=>$currentdate]);
		if(!$model){
			//从未加过金币则写记录
			$model = new CointaskService();
			$model->uid = $uid;
			$model->tasktype = $tasktype;
			$model->taskdate = $currentdate;
			$model->times = 1;
			$model->dataremark =$correctid;
			$model->ctime = time();
			if($model->save()){
				return true;
			}
			return false;
		}
		//判断当天排行榜加金币次数是否已到上限
		$maxtimes= CointaskDictService::getTaskTimes($tasktype);
		if($model->times >=$maxtimes){
			return false;
		}
		//加金币次数加1
		$model->times+=1;
		$model->dataremark .= ',' . $correctid;
		$model->save();		
		return true;
	}
	
	/**
	 * 获取上一次添加连续批改金币的日期是否大于指定天数
	 * @param unknown $uid
	 * @param unknown $days
	 * @return unknown|number
	 */
	static function moreLastCorrectTaskTime($uid,$days){
		$model = (new \yii\db\Query())
		->select(['taskdate'])
		->from(parent::tableName())
		->where(['uid'=>$uid])
		->andWhere(['tasktype'=>CointaskTypeEnum::CONTINUE_CORRECT])
		->orderBy('taskid DESC')
		->one();
		if(!$model){
			//未加过金币
			return true;
		}
		$lasttime =  $model['taskdate'];
		//今天时间戳
		$currentdate = time();
		if(($currentdate-$lasttime) > $days*24*3600){
			//已超过加金币日期间隔
			return true;
		}
		return false;
	}
	
	/**
	 * 判断一次性加金币的任务是否可以加金币
	 * @param unknown $uid
	 * @param unknown $tasktype
	 * @return boolean
	 */
	static function IsAddByOneTime($uid,$tasktype){
		//获取用户当天加金币次数
		$model = static::findOne(['uid'=>$uid,'tasktype'=>$tasktype]);
		if(!$model){
			//未加过金币则写记录
			$model = new CointaskService();
			$model->uid = $uid;
			$model->tasktype = $tasktype;
			$model->taskdate = time();
			$model->times =1;
			$model->ctime = time();
			if($model->save()){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 记录连续5天求批改记录
	 * @param unknown $uid
	 */
	static function saveLastCorrect($uid){
		$model = new CointaskService();
		$model->uid = $uid;
		$model->tasktype = CointaskTypeEnum::CONTINUE_CORRECT;
		$model->taskdate = strtotime(date("Y-m-d"));
		$model->times =1;
		$model->ctime = time();
		return $model->save();
	}
	
	/**
	 * 金币任务的返回数组格式，
	 * 所有任务返回前从此处封装数据
	 * @param unknown $taskType
	 * @param unknown $coinCount
	 */
	static function getReturnData($taskType,$coinCount){
		$ret['tasktype'] = $taskType;
		$ret['cointcount'] = $coinCount;
		return $ret;
	}
}