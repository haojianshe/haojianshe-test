<?php
namespace api\modules\v2_3_7\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelMaterialZanService;
use api\service\CapacityModelMaterialService;
use api\service\UserDetailService;
/**
 * 能力模型素材点赞
 */
class ZanAddAction extends ApiBaseAction
{
    public function run()
    {   
        $materialid = $this->requestParam('materialid',true); 
        //获取能力模型信息
        $matreial_info=CapacityModelMaterialService::getMatreialDetail($materialid,$this->_uid);
        if(!$matreial_info){
			return $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        }
        $zan=CapacityModelMaterialZanService::findOne(["materialid"=>$materialid,"uid"=>$this->_uid]);
        if($zan){
        	$data['message']="已经点赞过了!";
        	return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $model=new CapacityModelMaterialZanService();
		$model->uid=$this->_uid;
		$model->materialid=$materialid;
		$model->username=$matreial_info['sname'];
		$model->owneruid=$matreial_info['uid'];
		$model->ctime=time();
		$ret=$model->save();
        $data['user_info']=UserDetailService::getByUid($this->_uid);
		if($ret){
			return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
		}else{
			return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
		}		
    }
}
