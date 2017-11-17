<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\RecommendBookAdvService;
use api\service\PublishingBookService;
/**
 * 分类图书推荐广告（个人中心图书 素材 能力模型素材下推荐） 
 */
class PublishingBooksAdvAction extends ApiBaseAction
{
    public function run()
    {   
    	//能力模型/出版社推荐 -1/其他 能力模型推荐/出版社推荐
    	$uid = $this->requestParam('uid',true); 
    	// 0/4/5/1 个人中心/素描/速写/色彩
    	$adv_type = $this->requestParam('adv_type',true); 
        $bookids=RecommendBookAdvService::getRecommendAdvList($adv_type,$uid);
        $data=PublishingBookService:: getPublishingBooksInfo($bookids);
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
