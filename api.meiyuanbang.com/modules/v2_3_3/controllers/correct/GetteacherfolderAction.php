<?php
namespace api\modules\v2_3_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectTeacherFolderService;

/**
 *  
 * 获取老师范例图目录列表
 */
class GetteacherfolderAction extends ApiBaseAction
{
	public function run()
    {
        //获取批改实体和帖子实体
        $data = CorrectTeacherFolderService::getAllFolder($this->_uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }  
}