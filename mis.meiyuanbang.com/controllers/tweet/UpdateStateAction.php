<?php
namespace mis\controllers\tweet;

use Yii;
use mis\components\MBaseAction;
use mis\service\TweetService;

/**
 *删除帖子
 */
class UpdateStateAction extends MBaseAction
{ 
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
    	$tid = $request->post('tid');
    	if(!$tid || !is_numeric($tid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = TweetService::findOne(['tid' => $tid]);
        if(!empty($request->post('flag')) ||$request->post('flag')==0){
             $model->flag=$request->post('flag');                
        }

        if(!empty($request->post('is_del'))){
            $model->is_del=$request->post('is_del');    
        }
    	if($model){
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'更改失败']);
    
    }
}
