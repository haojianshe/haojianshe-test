<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvResourceService;
use mis\service\AdvUserService;
use mis\service\AdvRecordService;

class DelAction extends MBaseAction
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
    	$advid = $request->post('advid');
    	if(!$advid || !is_numeric($advid)){
    		die('参数不正确');
    	}

    	//根据id取出数据
    	$model = AdvResourceService::findOne(['advid' => $advid]);
        //判断是否正在投放 或者投放过
        $record=AdvRecordService::find()->where(['advid' => $advid])->andWhere(['status'=>1])->all();
        if($record){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>'广告使用中... ']);
        }
    	if($model){
    		$model->status=1;
    		$ret = $model->save();
    		if($ret){
                $advuser=AdvUserService::findOne(['advuid' => $model->advuid]);
                if($advuser->advcount>0){
                    $advuser->advcount=$advuser->advcount-1;
                    $advuser->save();
                }
                
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
