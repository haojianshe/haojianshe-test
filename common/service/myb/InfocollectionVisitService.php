<?php
namespace common\service\myb;

use Yii;
use common\models\myb\InfocollectionVisit;
use common\service\myb\InfocollectionUserService;

/**
 * 
 * @author Administrator
 * 付费用户内容被访问的记录
 */
class InfocollectionVisitService extends InfocollectionVisit
{
	/**
	 * 记录访问日志
	 * @param unknown $visituid
	 * @param unknown $collectionuid
	 * @param unknown $subjecttype
	 * @return unknown
	 */
	static function writeVisitRecord($visituid,$collectionuid,$subjecttype) {
		//检查访问者id是否合法
		if($visituid<=0){
			return;
		}
		//判断用户是否需要被采集
		$collectionusers = InfocollectionUserService::getCollectusers();
		if($collectionusers && in_array($collectionuid, $collectionusers)){
			//当前日期的时间戳
			$today = strtotime(date("Y-m-d"));
			$model = static::findOne(['visit_uid' => $visituid,'collection_uid' => $collectionuid,'subjecttype' => $subjecttype,'visitdate'=>$today]);
			if($model){
				//用户今天已经被采集过
				$model->visit_num = $model->visit_num+1;
				$model->lastvisttime = time();
				$model->save();
			}
			else {
				//用户今天第一次被采集过
				$model=new InfocollectionVisitService();
				$model->visit_uid=$visituid;
				$model->collection_uid=$collectionuid;
				$model->subjecttype=$subjecttype;
				$model->visit_num=1;
				$model->visitdate=$today;
				$model->ctime=time();
				$model->lastvisttime=$model->ctime;
				$model->save();
			}
		}		
	}
}