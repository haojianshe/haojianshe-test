<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\RecommendBookService;
use api\service\PublishingBookService;
/**
 * 美院帮推荐图书列表
 */
class MybRecommendBooksAction extends ApiBaseAction
{
    public function run()
    {   
        
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
        $last_id=$this->requestParam('last_id') ? $this->requestParam('last_id'): NULL;
        $f_catalog_id=$this->requestParam('f_catalog_id') ? $this->requestParam('f_catalog_id'): 0;
        //$f_catalog_id = $this->requestParam('f_catalog_id',true); 
        $bookids=RecommendBookService::getRecommendBooksList($f_catalog_id,$last_id,$rn);
        $data['content']=PublishingBookService:: getPublishingBooksInfo($bookids);
        //分享信息
        $share['title']="美院帮书籍推荐";
        $share['desc']="帮叔挑选海量优质书籍，助亲快速提高绘画技巧、有效提升绘画能力";
        $share['img']="";
        $share['url']=Yii::$app->params['sharehost']."/publishing/myb_recommend_books";
        $data['share']=$share;
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
