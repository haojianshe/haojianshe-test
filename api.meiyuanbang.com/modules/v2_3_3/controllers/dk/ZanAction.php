<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;
use common\models\myb\DkCorrect;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 点赞
 */
class ZanAction extends ApiBaseAction {
    
    public function run() {
        //批改id
        $dkcorrectid = $this->requestParam('dkcorrectid', true);

        $model=DkCorrect::findOne(['dkcorrectid'=>$dkcorrectid]);
        $model->zan_num=$model->zan_num+1;
        $res=$model->save();

        if($res){
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }

    }

}
