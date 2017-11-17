<?php
namespace mis\controllers\holidayicons;

use Yii;
use mis\components\MBaseAction;
use mis\service\HolidayIconsService;

/**
 * 更新节日图标状态
 */
class UpdateAction extends MBaseAction
{	
	public $resource_id = 'operation_icons';
	
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
    	$id = $request->post('id');
    	if(!$id || !is_numeric($id)){
    		die('参数不正确');
    	}
        $status = $request->post('status');
    	//根据id取出数据
    	$model = HolidayIconsService::findOne(['iconsid' => $id]);
    	if($model){
            if($status==2){
                $model->status =2;
                $ret = $model->save();
            }elseif($status==3){
                $allmodel=HolidayIconsService::findAll(['status'=>3]);
                foreach ($allmodel as $key => $value) {
                    $value->status=1;
                    $value->save();
                }
                $model->status =3;
                $ret = $model->save();
            }elseif($status==1){
                $model->status =1;
                $ret = $model->save();
            }
            $redis = Yii::$app->cache;
            $rediskey = "holidayicons";
            $redis->delete($rediskey);
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
