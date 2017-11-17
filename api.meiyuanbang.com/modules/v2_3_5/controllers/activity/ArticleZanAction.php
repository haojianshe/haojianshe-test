<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\NewsData;
/**
 * 活动文章点赞
 */
class ArticleZanAction extends ApiBaseAction
{
    public function run()
    {   
        $newsid = $this->requestParam('newsid',true); 
        $news_type = $this->requestParam('news_type',true); 
        
        $model=NewsData::findOne(["newsid"=>$newsid]);
        if($model){
        	//增加点暂数
        	$model->supportcount=$model->supportcount+1;
        	$ret=$model->save();

        	if($ret){
                $redis = Yii::$app->cache;
                // 1/2 文章/问答
                switch ($news_type) {
                    case 2:
                            $redis->hincrby('activity_qa_'.$newsid, 'supportcount', 1); 
                        break;
                    case 1:
                            $redis->hincrby('activity_article_'.$newsid, 'supportcount', 1); 
                        break;
                    default:
                        break;
                }
                
        		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        	}else{
        		return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        	}
        }else{
        	return $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        	
        }
    }
}
