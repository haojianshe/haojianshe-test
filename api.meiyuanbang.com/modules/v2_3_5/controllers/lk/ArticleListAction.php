<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityArticleService;
use api\service\LkMaterialRelationService;
use common\service\DictdataService;

/**
 * 联考文章列表
 */
class ArticleListAction extends ApiBaseAction
{
    public function run()
    {   
        echo (DictdataService::getTweetTypeAndTagStr())  ;
        exit;      
        $lkid = $this->requestParam('lkid',true); 
        //最后一个id
        $last_newsid = $this->requestParam('last_newsid'); 
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10; 
        //得到文章id
        $newsids=LkMaterialRelationService::getArticleList($lkid,"all",$last_newsid,$rn);
        //得到文章信息
        $data=LkMaterialRelationService::getAllLkArticleInfo($lkid,$newsids);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
