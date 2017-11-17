<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use console\service\NewsDataService;
use console\service\NewsService;
use console\service\LectureService;

/**
 * 启动pushservice守护进程
 */
class LecturePublishAction extends Action
{
    public function run()
    {
    	//3.0.2版本开始，不在需要变更id来定时发布,线上应去掉此定时任务
    	return;
    	
    	//获取已到定时发布时间的精讲文章id
    	$time = time();
    	$ids =LectureService::getByPublishTime($time);
    	if(!$ids){
    		return;
    	}
    	foreach ($ids as $k=>$v){
    		//重新发布news表
    		$model = NewsService::findOne(['newsid'=>$v['newsid']]);
    		$oldid = $model->newsid;
    		$model->newsid = null;
    		$model->isNewRecord = true;
    		$model->save();
    		//更新newsdate表
    		NewsDataService::update_newsid($model->newsid, $oldid);
    		//更新lecture表
    		LectureService::publish($model->newsid, $oldid);
    		//删除老的news记录
    		$model = NewsService::findOne(['newsid'=>$v['newsid']]);
    		$model->delete();
    		//清除缓存
    		LectureService::removeListCache();    		
    	}
    }    
}