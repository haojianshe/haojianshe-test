<?php
namespace mis\controllers\znarticle;

use Yii;
use mis\components\MBaseAction;
use mis\service\ZhnArticleService;

/**
 * 
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_zhn';
	
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
    	$newsid = $request->post('newsid');
    	if(!$newsid || !is_numeric($newsid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = ZhnArticleService::findOne(['newsid' => $newsid]);
    	if($model){
    		$model->status =1;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
