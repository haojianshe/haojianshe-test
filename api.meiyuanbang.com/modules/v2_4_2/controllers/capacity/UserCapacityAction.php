<?php
namespace api\modules\v2_4_2\controllers\capacity;

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
use api\service\UserDetailService;
use api\service\UserCorrectService;
use common\service\dict\CapacityModelDictDataService;

/**
 * 获取用户能力模型图
 */
class UserCapacityAction extends ApiBaseAction
{
    public function run()
    {  
        
    	//根据主类型
    	$maintypeid = $this->requestParam('maintypeid',true);
        $uid=$this->_uid;
        $capacityModels = [];
        //登陆用户判断用户角色 未登录用户按照无能力模型处理
        if($uid>0){
            $userinfo=UserDetailService::getByUid($uid);
            //只有普通用户有能力模型数据
            if($userinfo['ukind']==0 && $userinfo['featureflag']!=1){
                $tmp = CapacityModelService::getUserCapacityModel($uid,$maintypeid);
                if($tmp){
                    $tmp = CapacityModelService::addInfoToModel($tmp);
                    $capacityModels[] = $tmp;
                }
            }
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
            $lessons_capacity=$this->getLesson($maintypeid);
            if($lessons_capacity){
                $ret['lesson'][] = $lessons_capacity; 
            }else{
                $ret['lesson']=[];
            }
            
            //推荐的排行榜信息  
            $data=CorrectService::getCorrectScoreRankRedis();
            $tmp = json_decode($data,true);
            if($tmp && $tmp['f'.$maintypeid] && count($tmp['f'.$maintypeid])>0){
                foreach ($tmp['f'.$maintypeid] as $key => $value) {
                    $tmp1 =  CorrectService::getFullCorrectInfo($tmp['f'.$maintypeid][$key]['correctid'],$this->_uid);
                    $tmp1['ranktype']=CapacityModelDictDataService::getCorrectMainTypeNameById($maintypeid);
                    $ret['recommend'][] = $tmp1;
                }
            }
            $ret['capacitymodels'] = $capacityModels;
            //能力模型素材 对应一级分类 所有能力分类下数据
            $itemids=CapacityModelDictDataService::getCorrectScoreItemByMainId($maintypeid);
            foreach ($itemids as $key_item => $value_item) {
                $itemids[$key_item]['material']=CapacityModelMaterialService::getRecommend($maintypeid, 0, $value_item['itemid'], 6);
            }
            $ret['capacitymaterial'] = $itemids;
            //返回普通素材
            $normalMaterial = [];
            $tids = TweetService::getRecommendMaterialIds($maintypeid, 0,6);
            foreach($tids as $tid){
                $tmp = TweetService::getTweetListDetailInfo($tid,$uid);
                if($tmp){
                    $normalMaterial[]= $tmp;
                }
            }
            $ret['normalmaterial'] = $normalMaterial;
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
        //有能力模型，取对应一二级分类最新一条精讲
        $ret['lecture'] = LectureService::getRecommend($fcatalogid, $lastSCatalogid);
        if(!$ret['lecture']){
            //如果根据类型没取到则取最新的
            $ret['lecture'] = LectureService::getRecommend(0, 0);
        }
        //取同主类型的最新两条跟着画，无法同步到二级分类因为不是所有
        $lessons_capacity=$this->getLesson($maintypeid,$lastSCatalogid);
        if($lessons_capacity){
            $ret['lesson'][] = $lessons_capacity; 
        }else{
            $ret['lesson']=[];
        }
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
        $lessionids=[];

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
    	}else{
    		$lessionids = LessonService::getIdsByMainType($maintypeid,2);
    	}    	
        $ret=[];
    	if($lessionids){
           $ret['maintype']=$maintypeid;
            foreach ($lessionids as $k=> $v){
                //获取考点信息和第一张展示图
                $arrlesson = LessonService::getLessonWithFirstPic($v);
                if($arrlesson){
                    $ret['list'][]=$arrlesson;
                }
            } 
        }
    	
    	return $ret;
    }
}
