<?php
namespace api\modules\v2_3_7\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelMaterialZanService;

/**
 * 点赞用户列表
 */
class MaterialZanlListAction extends ApiBaseAction
{
    public function run()
    {   
        $materialid = $this->requestParam('materialid',true); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 30;
		$last_id=$this->requestParam('last_id') ? $this->requestParam('last_id'): NULL;
		//获取点赞用户列表
        $data['content']=CapacityModelMaterialZanService::getZanUserList($materialid,$last_id,$rn);
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
