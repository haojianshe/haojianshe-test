<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkCorrectService;

/**
 * 删除活动模块
 */
class DelCorrectAction extends MBaseAction
{	
	public $resource_id = 'operation_activity';
	
    /**
     * 只支持post删除
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$dkcorrectid = $request->post('dkcorrectid');
    	if(!$dkcorrectid || !is_numeric($dkcorrectid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = DkCorrectService::findOne(['dkcorrectid' => $dkcorrectid]);
    	if($model){
    		//$model->status =2;
    		$ret = $model->delete();

    		if($ret){
                $redis = Yii::$app->cache;
                $rediskey="dkcorrectlist".$model['activityid'];
                $redis->delete($rediskey);
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
