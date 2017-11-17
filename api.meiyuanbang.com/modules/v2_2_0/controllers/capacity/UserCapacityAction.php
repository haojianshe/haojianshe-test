<?php
namespace api\modules\v2_2_0\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelService;
use api\service\CapacityModelMaterialService;
use api\service\TweetService;

/**
 * 获取用户能力模型图
 */
class UserCapacityAction extends ApiBaseAction
{
    public function run()
    {   
    	//根据主类型
    	$maintypeid = $this->requestParam('maintypeid');
    	if(! $maintypeid){
    		$maintypeid=0;
    	}
    	//如果maintype=0，则判断用户有哪些类型的模型图
    	$capacityModels = [];
    	switch ($maintypeid){
    		case 0:
    			//取能力模型
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 4);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp; 
    				$maintypeid = 4;
    			}
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 1);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp;
    				$maintypeid = 1;
    			}
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 5);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp;
    				$maintypeid = 5;
    			}
    			break;
    		case 1:
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 1);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp;
    			}
    			break;
    		case 4:
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 4);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp;
    			}
    			break;
    		case 5:
    			$tmp = CapacityModelService::getUserCapacityModel($this->_uid, 5);
    			if($tmp){
    				$tmp = CapacityModelService::addInfoToModel($tmp);
    				$capacityModels[] = $tmp;
    			}
    			break;
    	}
    	if(count($capacityModels)==0){
    		//没有能力模型返回空
    		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);    
    	}
    	

    	$capacityMaterial = [];
    	$capacityModel = $capacityModels[0];
    	//按照得分顺序，返回能力模型素材
    	foreach ($capacityModel['capacity'] as $k=>$v){
    		$tmp = [];
    		$tmp['itemname']= $v['itemname'];
    		$tmp['score']= $v['score'];
    		$tmp['itemid']= $v['itemid'];
    		$capacityMaterial[] = $tmp;
    	}    	
    	$capacityMaterial = $this->sortByScore($capacityMaterial);
    	foreach ($capacityMaterial as $k=>$v){
    		//添加能力素材
    		$capacityMaterial[$k]['material']=CapacityModelMaterialService::getRecommend($capacityModel['catalogid'], $capacityModel['last_correct_scatalogid'], $v['itemid'], 6);
    	}
    	//返回普通素材
    	$normalMaterial = [];
    	$tids = TweetService::getRecommendMaterialIds($capacityModel['catalogid'], $capacityModel['last_correct_scatalogid'],6);
    	foreach($tids as $tid){
    		$tmp = TweetService::getTweetListDetailInfo($tid,$this->_uid);
    		if($tmp){
    			$normalMaterial[]= $tmp;
    		}
    	}
    	$ret['capacitymodels'] = $capacityModels;
    	$ret['capacitymaterial'] = $capacityMaterial;
    	$ret['normalmaterial'] = $normalMaterial;
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    	
    }
    
    /**
     * 根据得分由低到高排序
     * @param unknown $capacityMaterial
     */
    private function sortByScore($capacityMaterial){
    	$ret = [];
    	$arr = [];
    	foreach ($capacityMaterial as $k=>$v){
    		$arr[$k] = $v['score'];
    	}
    	asort($arr);
    	foreach ($arr as $k=>$v){
    		$ret[] = $capacityMaterial[$k];
    	}
    	return $ret;
    }
}
