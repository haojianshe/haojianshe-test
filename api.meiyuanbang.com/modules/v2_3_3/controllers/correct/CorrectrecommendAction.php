<?php
namespace api\modules\v2_3_3\controllers\correct;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;
use api\service\LessonService;
use api\service\LiveService;
use api\service\CourseService;
use api\service\UserDetailService;
use api\service\CapacityModelService;
use common\service\dict\CapacityModelDictDataService;
use api\service\CapacityModelMaterialService;
use api\service\CourseSectionVideoService;

/**
 * 获取分页排行榜数据
 * @author Administrator
 *
 */
class CorrectrecommendAction extends ApiBaseAction{
    public  function run(){
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//批改id
    	$correctid = $this->requestParam('correctid',true);
    	//获取当前批改的信息
    	$model = CorrectService::getFullCorrectInfo($correctid,$this->_uid);
        //推荐课程
        if($model['recommend_courseids']){
            //老师推荐
            $courseids=explode(",", $model['recommend_courseids']);
            $ret['course']=CourseService::getListDetail($courseids,$this->_uid);
        }else{
            //系统推荐
            $ret['course']=$this->getCourse($this->_uid,$correctid,$model['f_catalog_id'],$model['s_catalog_id']);
        }
    	$ids = CorrectService::getRecommendIdsByCorrectId($correctid, $model['f_catalog_id'], $model['s_catalog_id'], $rn);
        if($this->_uid==$model['submituid']){

            //本人看
            //直播课匹配到二级分类最新两个，
            //课程匹配到二级分类随机两个（逻辑参照跟着画推荐）
            $ret['content']=[];
            $ret['live']=$this->getLive($correctid,$model['f_catalog_id'],$model['s_catalog_id'],$this->_uid);
           /* $ret['course']=$this->getCourse($correctid,$model['f_catalog_id'],$model['s_catalog_id']);*/

            $ret['capacitymodels']=$this->getCapacityModel($model);

        }else{
            //别人看
            //优先匹配与批改二级分类相同的课程随机取两个
            //（逻辑参照跟着画推荐逻辑）
            $ret['live']=[];
            $ret['capacitymodels']=[];
            /*$ret['course']=$this->getCourse($correctid,$model['f_catalog_id'],$model['s_catalog_id']);*/
            //获取数据 精彩批改
            $ret['content'] = [];
            if($ids){
                foreach ($ids as $key => $value) {
                    $tmp = CorrectService::getFullCorrectInfo($value,$this->_uid);
                    if($tmp){
                        $ret['content'][]=$tmp;
                    }               
                }   
            }
        }
		if($this->requestParam('devicetype')=="ios" && $this->requestParam('com_version')=="302"){
            $ret['live']=[];
        }
		//增加跟着画推荐
		$ret['lesson']= $this->getLesson($correctid,$model['f_catalog_id'],$model['s_catalog_id']);
				
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    /**
     * 获取推荐直播 直播课匹配到二级分类最新两个，
     * @param  [type]  $maintypeid [description]
     * @param  integer $subtypeid  [description]
     * @return [type]              [description]
     */
    private function getLive($correctid,$maintypeid,$subtypeid = 0,$uid=-1){
        $ret=[];
        $liveids=LiveService::getLiveByCorrectidRedis($correctid,$maintypeid,$subtypeid);
        $ret=LiveService::getListDetail($liveids, $uid);
        return $ret;
    }
    /**
     * 获取推荐课程
     * @param  [type]  $maintypeid [description]
     * @param  integer $subtypeid  [description]
     * @return [type]              [description]
     */
    private function getCourse($uid,$correctid,$maintypeid,$subtypeid = 0){
        $courseids=CourseService::getCourseByCorrectidRedis($correctid,$maintypeid,$subtypeid);
        /*$courseids=CourseService::getCourseidByCatalogRedis($maintypeid,$subtypeid);*/
        $ret=CourseService::getListDetail($courseids,$uid);
        return $ret;
    }
    /**
     * 获取推荐跟着画
     * 先根据主类型 分类型查找，如果未找到则根据主类型查找
     * @param unknown $maintypeid
     * @param unknown $subtypeid
     * @return Ambigous <unknown, NULL, number, \common\service\Ambigous>
     */
    private function getLesson($correctid,$maintypeid,$subtypeid = 0){
    	//根据子类型匹配
    	if($subtypeid){
    		$lessionids = LessonService::getIdsByTypeLimit($maintypeid, $subtypeid,$correctid,2);
    	}
    	if(count($lessionids)<2){
    		$lessionids = LessonService::getIdsByMainType($maintypeid,2,$correctid);
    	}
        if(empty($lessionids)){
            $ret=[];
        }else{
            foreach ($lessionids as $k=> $v){
                //获取考点信息和第一张展示图
                $arrlesson = LessonService::getLessonWithFirstPic($v);
                if($arrlesson){
                    $ret[]=$arrlesson;
                }
            }
        }
    	return $ret;
    }
    /**
     * 得到推荐能力模型素材
     * @param  [type] $maintypeid [description]
     * @param  [type] $uid        [description]
     * @return [type]             [description]
     */
    private function getCapacityModel($corrrect_model){
        $maintypeid=$corrrect_model['f_catalog_id'];
        $subtypeid=$corrrect_model['s_catalog_id'];
        $correctid=$corrrect_model['correctid'];

        $capacityModels = [];
      
        //获取能力模型打分项
        $scoreitems=CapacityModelDictDataService::getCorrectScoreItem()[$maintypeid];
        //判断是否有分数 若有分数 则按照分数排序推荐 若没有按照默认排序推荐
        if($corrrect_model['markdetail']){
            //获取处理能力模型打分项数据
            $capacityMaterial = json_decode($corrrect_model['markdetail'],true);
            foreach ($capacityMaterial as $k => $v) {
               foreach ($scoreitems as $k1 => $v1) {
                    //取得每一项的名称
                    if($v1['itemid']==$v['itemid']){
                        $capacityMaterial[$k]['itemname']=$v1['itemname'];
                    }
               }
            }
            //根据分数排序 打分项
            $capacityMaterial = $this->sortByScore($capacityMaterial);
        }else{
            foreach ($scoreitems as $k1 => $v1) {
                unset($scoreitems[$k]['weight']);
            }
            $capacityMaterial=$scoreitems;
        }
        //根据能力模型打分项 获取每项对应分类推荐的能力模型素材
        $tmp = [];
        foreach ($capacityMaterial as $k=>$v){
            //添加能力素材
            $item_material=CapacityModelMaterialService::getRecommendByCorrectid($correctid,$maintypeid, $subtypeid, $v['itemid'], 6);
            if(!empty($item_material)){
                $v['material'] = $item_material;
                $tmp[]= $v;
            }
        }

        $capacityMaterial=$tmp;
        return $capacityMaterial;
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