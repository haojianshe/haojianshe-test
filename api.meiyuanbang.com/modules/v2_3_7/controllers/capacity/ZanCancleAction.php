<?php
namespace api\modules\v2_3_7\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelMaterialZanService;
use api\service\CapacityModelMaterialService;

/**
 * 能力模型素材取消点赞
 */
class ZanCancleAction extends ApiBaseAction
{
    public function run()
    {   
        $materialid = $this->requestParam('materialid',true); 
        $zan=CapacityModelMaterialZanService::findOne(['materialid'=>$materialid,"uid"=>$this->_uid]);
        if($zan){
        	$ret=$zan->delete();
        	$redis = Yii::$app->cache;
        	$redis_key ='capacitymodelmaterialzan_list_'.$materialid; 
        	$redis->delete($redis_key); 
        	if(!$ret){
        		return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        	}
        }
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }
}
