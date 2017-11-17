<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;
use common\lib\myb\enumcommon\CointaskTypeEnum;

/**
 * 金币任务字典数据
 */
class CointaskDictService extends Object
{    
	
	/**
	 * 获取金币任务对应的金币数
	 * @param unknown $taskType
	 */
	static function getCoinCount($taskType){
		$ret = 0;
		switch ($taskType){
			//新用户首次注册
			case  CointaskTypeEnum::FIRST_REGISTER :
				$ret = 50;
				break;
			//第三方用户注册
			case  CointaskTypeEnum::THIRDPART_REGISTER :
				$ret = 70;
				break;
			//账号关联
			case  CointaskTypeEnum::USER_UNION :
				$ret = 20;
				break;
			//完善注册信息
			case  CointaskTypeEnum::FINISH_REGIST_INFO :
				$ret = 50;
				break;
			//首次完善个人资料
			case  CointaskTypeEnum::FINISH_USERINFO :
				$ret = 50;
				break;
			//学生超三次求批改
			case  CointaskTypeEnum::MORE_CORRECT :
				$ret = -10;
				break;
			//加入小组 一次性
			case  CointaskTypeEnum::ADD_TEAM :
				$ret = 10;
				break;
			//学生上传作品
			case  CointaskTypeEnum::USER_TWEET :
				$ret = 5;
				break;
			//学生上传作品
			case  CointaskTypeEnum::CONTINUE_CORRECT :
				$ret = 100;
				break;
			//进入排行榜
			case  CointaskTypeEnum::RANK_LIST :
				$ret = 50;
				break;
			//老师上传作品
			case  CointaskTypeEnum::TEACHER_TWEET :
				$ret = 100;
				break;
			//分享
			case  CointaskTypeEnum::SHARE :
				$ret = 30;
				break;
			//意见反馈
			case  CointaskTypeEnum::ADVISE :
				$ret = 10;
				break;
			//评论
			case  CointaskTypeEnum::COMMENT :
				$ret = 2;
				break;
			//关注他人
			case  CointaskTypeEnum::FOLLOW :
				$ret = 1;
				break;
		}
		return $ret;
	}
	
	/**
	 * 获取可以加分的次数
	 * @param unknown $taskType
	 */
	static function getTaskTimes($taskType){
		$ret = 1;
		switch ($taskType){
			//学生上传作品
			case  CointaskTypeEnum::USER_TWEET :
				$ret = 10;
				break;
			//进入排行榜
			case  CointaskTypeEnum::RANK_LIST :
				$ret = 3;
				break;
			//老师上传作品
			case  CointaskTypeEnum::TEACHER_TWEET :
				$ret = 10;
				break;
			//分享
			case  CointaskTypeEnum::SHARE :
				$ret = 10;
				break;
			//意见反馈
			case  CointaskTypeEnum::ADVISE :
				$ret = 1;
				break;
			//评论
			case  CointaskTypeEnum::COMMENT :
				$ret = 10;
				break;
			//关注他人
			case  CointaskTypeEnum::FOLLOW :
				$ret = 10;
				break;
		}
		return $ret;
	}
	
	/**
	 * 获取任务提示语
	 * @param unknown $taskType
	 */
	static function getTaskMessage($taskType){
	
	}
}
