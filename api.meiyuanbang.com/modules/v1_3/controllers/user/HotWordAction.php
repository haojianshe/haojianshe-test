<?php
namespace api\modules\v1_3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use api\service\UserRelationService;

/**
 * 用户搜索
 * @author Administrator
 *
 */
class HotWordAction extends ApiBaseAction
{
	public function run()
    {    	
    	$ret[]['word'] = '刘太冉';
        $ret[]['word'] = '美院帮管家';
    	//$ret[]['word'] = '帮小美';
    	//$ret[]['word'] = '帮星人';
    	//$ret[]['word'] = '美院帮官方运营号';
    	
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['words'=>$ret]);
    }
}
