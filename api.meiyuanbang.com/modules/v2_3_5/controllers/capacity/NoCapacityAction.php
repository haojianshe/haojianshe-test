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
 * 用户无能力模型图时推荐的内容
 */
class NoCapacityAction extends ApiBaseAction
{
    public function run()
    {   
    	//取能力模型页的广告，类型为4
    	$ret['ad'] = PosidHomeService::getPosidHomeList(4);    	 
    	//没有能力模型，取最新一条精讲
   		$ret['lecture'] = LectureService::getRecommend(0, 0);
   		//取最新两条跟着画
   		$ret['lesson_nocap'][] = $this->getLesson(4); //素描
   		$ret['lesson_nocap'][] = $this->getLesson(1); //色彩
   		$ret['lesson_nocap'][] = $this->getLesson(5); //速写   		
   		//推荐的排行榜信息
   		//获取排行榜批改id /*$ret = ['1' => "色彩", '4' => "素描",'5' => "速写"];*/
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
      //推荐书籍
      $bookids=RecommendBookAdvService::getRecommendAdvList(0,-1);
      $ret['book_adv']=PublishingBookService::getPublishingBooksInfo($bookids);

   		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret); 	
    }
    
    private function getLesson($maintypeid){
    	$lessionids = LessonService::getIdsByMainType($maintypeid,2);
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
