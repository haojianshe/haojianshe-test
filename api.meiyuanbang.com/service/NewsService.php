<?php
namespace api\service;

use Yii;
use common\redis\Cache;
use common\models\myb\News;
use api\service\ResourceService;
use common\service\CommonFuncService;

/**
 * 
 * @author Administrator
 *
 */
class NewsService extends News
{
    public static function getLectureInfo($newsid){
        $lecture=parent::findOne(["newsid"=>$newsid,"catid"=>0]);
        //判断是否存在 
        if($lecture){
            $lecture_info=$lecture->attributes;
        }else{
            return false;
        }
        $rids = explode(',', $lecture_info['thumb']);
        foreach($rids as $rid) {
            $resourcemodel = ResourceService::findOne(['rid'=>$rid]);
            //容错，rid有效，并且img有效
            if(!$resourcemodel){
                continue;
            }
            if(empty($resourcemodel['img'])){
                continue;
            }
            $imgs[]=$resourcemodel->attributes;
        }
        $lecture_info['img']=$imgs;
        $lecture_info['url']=Yii::$app->params['sharehost'].'/lecture?id='.$newsid;
        return $lecture_info;
    }
    
    /**
     * 获取活动详细信息
     * @param unknown $newsid
     * @return boolean|string
     */
    public static function getActivityInfo($newsid){
    	$model=parent::findOne(["newsid"=>$newsid]);
    	//判断是否存在
    	if($model){
    		$model=$model->attributes;
    	}else{
    		return false;
    	}
    	//获取图片
    	$resourcemodel = ResourceService::findOne($model['thumb']);
    	//容错，rid有效，并且img有效	
    	$model['img']=$resourcemodel->attributes;
    	return $model;
    }
}