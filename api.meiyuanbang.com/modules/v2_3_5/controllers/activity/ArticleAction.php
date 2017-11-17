<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityArticleService;

/**
 * 文章详情
 */
class ArticleAction extends ApiBaseAction
{
    public function run()
    {   
        $newsid = $this->requestParam('newsid',true); 
        $data=ActivityArticleService::getArticleDetail($newsid,$this->_uid);
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
