<?php
namespace api\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 小组用户搜索
 */
class MemberSearchAction extends ApiBaseAction
{   
    public function run()
    {
       
      $data=TeamMemberService::members_search_db($this->requestParam('teamid',true),$this->requestParam('sname',true));
      foreach ($data as $key => $value) {        
        $data[$key]['avatar']=json_decode($data[$key]['avatar'])->img->n->url;
      }
      $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }
}
