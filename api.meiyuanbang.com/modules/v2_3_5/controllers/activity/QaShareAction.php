<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\QaShareRecord;

/**
 * 问答评论分享
 */
class QaShareAction extends ApiBaseAction
{
    public function run()
    {
    	$qaid = $this->requestParam('qaid',true); 
        $type = $this->requestParam('type'); 
        
    	$uid=$this->_uid;
    	$share_rec=QaShareRecord::find()->where(["uid"=>$uid,'qaid'=>$qaid])->asArray()->one();
    	if($share_rec){
            //已经分享过
            $data['is_share']=2;
    	}else{
            if(!$type=="get"){
                //分享
                $model=new QaShareRecord();
                $model->qaid=$qaid;
                $model->uid=$uid;
                $model->ctime=time();
                $ret=$model->save();
                if($ret){
                    $data['is_share']=2;
                }else{
                    return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                }
            }else{
                 $data['is_share']=1;
            }
    	}
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
