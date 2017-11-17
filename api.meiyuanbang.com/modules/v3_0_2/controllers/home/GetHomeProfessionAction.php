<?php
namespace api\modules\v3_0_2\controllers\home;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\UserHomeProfession;

/**
 * 获取首页用户角色
 * @author ihziluoh
 *
 */
class GetHomeProfessionAction extends ApiBaseAction{

   public  function run(){
        $model=UserHomeProfession::find()->where(['uid'=>$this->_uid])->asArray()->one();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$model);
    }
}