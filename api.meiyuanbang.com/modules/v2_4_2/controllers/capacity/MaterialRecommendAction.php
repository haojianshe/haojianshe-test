<?php
namespace api\modules\v2_4_2\controllers\capacity;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelMaterialService;

/**
 * 获取推荐数据
 * @author ihziluoh
 *
 */
class MaterialRecommendAction extends ApiBaseAction{
    public  function run(){
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//批改id
    	$materialid = $this->requestParam('materialid',true);
        //获取当前能力模型素材的信息
        $model = CapacityModelMaterialService::getMatreialDetail($materialid,$this->_uid);
        //分页
        $lastid = $this->requestParam('lastid');
        
        //判断是否有推荐 若没有则取之前的规则
        if($model['recommend_materialids']){
            $rec_ids=explode(",", $model['recommend_materialids']);
            //判断分页
            if($lastid){
                $rec_key=array_search($lastid, $rec_ids);
                $ids=array_slice($rec_ids,$rec_key+1,$rn);
            }else{
                $ids=array_slice($rec_ids,0,$rn);
            }
        }else{
            //默认规则分页
            if($lastid){
                $materialid=$lastid;
                $model = CapacityModelMaterialService::getMatreialDetail($materialid,$this->_uid);
            }        
            $ids = CapacityModelMaterialService::getRecommendIdsByMatreialId($materialid, $model['f_catalog_id'], $model['s_catalog_id'], $rn);
        }
                
        $publishing_ids=[];
        if($model['role_type']==2 && empty($lastid)){
            //获取出版社能力模型推荐id
            $publishing_ids=CapacityModelMaterialService::getPublishingMatreialId($model['uid'],$model['f_catalog_id'],$model['s_catalog_id'],2);
             //增加出版社能力模型推荐
            $ids=array_merge($publishing_ids,$ids);
        }
       
		//获取数据
    	$ret['content'] = [];
		if($ids){
			foreach ($ids as $key => $value) {
				$tmp = CapacityModelMaterialService::getMatreialDetail($value,$this->_uid);
				if($tmp){
					$ret['content'][]=$tmp;
				}				
			}	
		}				
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    
   
}