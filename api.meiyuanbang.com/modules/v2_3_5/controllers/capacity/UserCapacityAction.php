<?php
namespace api\modules\v2_3_5\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelService;
use api\service\CapacityModelMaterialService;
use api\service\TweetService;
use api\service\LectureService;
use api\service\LessonService;
use api\service\PosidHomeService;
use api\service\CorrectService;
use api\service\RecommendBookAdvService;
use api\service\PublishingBookService;
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
    	//取能力模型页的广告，类型为4
    	$ret['ad'] = PosidHomeService::getPosidHomeList(4);
        //推荐书籍
    	$bookids=RecommendBookAdvService::getRecommendAdvList($maintypeid,-1);
        $ret['book_adv']=PublishingBookService::getPublishingBooksInfo($bookids);

    	if(count($capacityModels)==0){
    		//没有能力模型，取最新一条精讲
    		$ret['lecture'] = LectureService::getRecommend(0, 0);    		
    		//取最新两条跟着画
    		$ret['lesson_nocap'][] = $this->getLesson(4); //素描
    		$ret['lesson_nocap'][] = $this->getLesson(1); //色彩
    		$ret['lesson_nocap'][] = $this->getLesson(5); //速写
    		//推荐的排行榜信息    		 
    		$data=CorrectService::getCorrectScoreRankRedis();
    		$tmp = json_decode($data);
    		if($tmp && $tmp->f4 && count($tmp->f4)>0){
    			$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f4[0]->correctid,$this->_uid);
    			$tmp1['ranktype'] = '素描';
    			$ret['recommend'][] = $tmp1;
    		}
    		if($tmp && $tmp->f1 && count($tmp->f1)>0){
    			$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f1[0]->correctid,$this->_uid);
    			$tmp1['ranktype'] = '色彩';
    			$ret['recommend'][] = $tmp1;
    		}
    		if($tmp && $tmp->f5 && count($tmp->f5)>0){
    			$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f5[0]->correctid,$this->_uid);
    			$tmp1['ranktype'] = '速写';
    			$ret['recommend'][] = $tmp1;
    		}
    		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    
    	}
    	
    	//有能力模型
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
    	$fcatalogid = $capacityModel['catalogid'];
    	$lastSCatalogid = $capacityModel['last_correct_scatalogid'];
    	foreach ($capacityMaterial as $k=>$v){
    		//添加能力素材
    		$capacityMaterial[$k]['material']=CapacityModelMaterialService::getRecommend($fcatalogid, $lastSCatalogid, $v['itemid'], 6);
    	}
    	//返回普通素材
    	$normalMaterial = [];
    	$tids = TweetService::getRecommendMaterialIds($fcatalogid, $lastSCatalogid,6);
    	foreach($tids as $tid){
    		$tmp = TweetService::getTweetListDetailInfo($tid,$this->_uid);
    		if($tmp){
    			$normalMaterial[]= $tmp;
    		}
    	}
    	$ret['capacitymodels'] = $capacityModels;
    	$ret['capacitymaterial'] = $capacityMaterial;
    	$ret['normalmaterial'] = $normalMaterial;
    	//没有能力模型，取最新一条精讲
    	$ret['lecture'] = LectureService::getRecommend($fcatalogid, $lastSCatalogid);
    	if(!$ret['lecture']){
    		//如果根据类型没取到则取最新的
    		$ret['lecture'] = LectureService::getRecommend(0, 0);
    	}
    	//取同主类型的最新两条跟着画，无法同步到二级分类因为不是所有
    	$ret['lesson'][] = $this->getLesson($fcatalogid,$lastSCatalogid);
    	//最新一条对应类型的批改
    	$correctid=CorrectService::getRecentUserCorrectidByMaintype($this->_uid, $maintypeid, 1)[0]['correctid'];
    	$ret['correct'] = CorrectService::getListDetailInfo($correctid);    	    	
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
    
    /**
     * 获取推荐跟着画
     * 先根据主类型 分类型查找，如果未找到则根据主类型查找
     * @param unknown $maintypeid
     * @param unknown $subtypeid
     * @return Ambigous <unknown, NULL, number, \common\service\Ambigous>
     */
    private function getLesson($maintypeid,$subtypeid = 0){
    	//根据子类型匹配
    	if($subtypeid){
    		$lessionids = LessonService::getIdsByType($maintypeid, $subtypeid);
    	}    	
    	if($lessionids && count($lessionids)>=2){
    		//随机取两个
    		$max = count($lessionids)-1;
    		$i1 = rand(0,$max);
    		$tmp[] = $lessionids[$i1];
    		while(count($tmp)<2){
    			$i = rand(0,$max);
    			if($i <>$i1){
    				$tmp[] = $lessionids[$i];
    			}
    		}
    		$lessionids = $tmp;
    	}
    	else{
    		$lessionids = LessonService::getIdsByMainType($maintypeid,2);
    	}    	
    	
    	$ret['maintype']=$maintypeid;
    	foreach ($lessionids as $k=> $v){
    		//获取考点信息和第一张展示图
    		$arrlesson = LessonService::getLessonWithFirstPic($v);
    		if($arrlesson){
    			$ret['list'][]=$arrlesson;
    		}
    	}
    	return $ret;
    }
}
