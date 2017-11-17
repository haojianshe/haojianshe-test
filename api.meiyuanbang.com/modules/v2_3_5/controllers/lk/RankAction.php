<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LkPaperService;
use api\service\LkPaperPicService;


/**
 * 模拟考榜单
 */
class RankAction extends ApiBaseAction
{
    public function run()
    {   
        $lkid = $this->requestParam('lkid',true); 
        $rank_type = $this->requestParam('rank_type',true);
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10; 
        if($rank_type=="all"){
            //分页最后一条记录id 
            $last_paperid = $this->requestParam('last_paperid'); 
            //总榜单
            $pagerids=LkPaperService::getPagerRankList($lkid,$last_paperid,$rn);
            $data=LkPaperService::getLkPagersInfo($pagerids);
        }else{
            //分页最后一条记录id 
            $last_picid = $this->requestParam('last_picid'); 
            //素描 速写 色彩榜单
            $pagerpicids=LkPaperPicService::getPagerPicRankList($lkid,$rank_type,$last_picid,$rn);
            $data=LkPaperPicService::getLkPaperPicsInfo($pagerpicids);
        }
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
