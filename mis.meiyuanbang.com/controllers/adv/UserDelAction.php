<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvUserService;
class UserDelAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {
       
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$advuid = $request->post('advuid');
    	if(!$advuid || !is_numeric($advuid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = AdvUserService::findOne(['advuid' => $advuid]);
    	if($model){
    		$model->status=1;
    		$ret =$model->save();
            $redis = Yii::$app->cache;
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}


