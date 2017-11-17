<?php
namespace api\modules\v2_4_2\controllers\tweet;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\TweetService;
use common\lib\myb\enumcommon\InfoCollectionSubjectTypeEnum;
use common\service\myb\InfocollectionVisitService;

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
    	$tid = $this->requestParam('tid',true);
        $lastid = $this->requestParam('lastid');
       
    	//获取当前素材的信息
    	$model = TweetService::fillExtInfo($tid,$this->_uid);
        //判断是否有推荐素材 若没有则取之前的规则
        if($model['recommend_tids']){
            $rec_ids=explode(",", $model['recommend_tids']);
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
                $tid=$lastid;
                $model = TweetService::fillExtInfo($tid,$this->_uid);
            }
            $ids = TweetService::getRecommendIdsByTid($tid, $model['f_catalog_id'], $model['s_catalog_id'], $rn);
        }

        $publishing_ids=[];
        if($model['role_type']==2 && empty($lastid)){
            //获取出版社素材推荐id
            $publishing_ids=TweetService::getPublishingRecommendIdsByUid($model['uid'],$model['f_catalog_id'],$model['s_catalog_id'],2);
            //增加出版社素材推荐
            $ids=array_merge($publishing_ids,$ids);
        }
		//获取数据
    	$ret['content'] = [];
		if($ids){
			foreach ($ids as $key => $value) {
				$tmp = TweetService::fillExtInfo($value,$this->_uid);
				if($tmp){
					$ret['content'][]=$tmp;
				}				
			}	
		}	
		//v3.2.3增加,指定用户记录素材被访问信息,此方法应该加到tweetdetail接口，因为在老版本接口中，所以先放在此处
		InfocollectionVisitService::writeVisitRecord($this->_uid, $model['uid'], InfoCollectionSubjectTypeEnum::MATERIAL);
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    
   
}