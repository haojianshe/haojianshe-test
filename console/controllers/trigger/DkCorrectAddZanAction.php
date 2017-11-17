<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use common\models\myb\DkCorrect;



/**
 * 启动pushservice守护进程
 */
class DkCorrectAddZanAction extends Action
{
    public function run()
    {
    	//获取已到定时发布时间
    	$time = time();
    	$ids =DkCorrect::find()->select("dkcorrectid")->where(['<','add_zan_time',$time])->all();
    	if(!$ids){
    		return;
    	}
    	foreach ($ids as $k=>$v){
    		//更改赞数
    		$model = DkCorrect::findOne(['dkcorrectid'=>$v['dkcorrectid']]);
    		$model->zan_num = $model->zan_num + $model->add_zan_count; 
            $model->add_zan_count=0;
            $model->add_zan_time=0;
    		$model->save();
    	}
    }    
}