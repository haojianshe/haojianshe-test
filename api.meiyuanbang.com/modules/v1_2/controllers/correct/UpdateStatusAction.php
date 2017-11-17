<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use common\redis\Cache;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\service\ResourceService;
use api\service\CorrectTalkService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 老师更改批改状态
 */
class UpdateStatusAction extends ApiBaseAction
{   
    public function run()
    {
        $request=Yii::$app->request;
        $redis = Yii::$app->cache;
        $status=$this->requestParam('status',true);
        $uid=$this->_uid;
        //接口不允许删除用户
        if($status==1){
            $data['message']='not allow delete user';
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL,$data);
        }
        //缓存读取用户信息
        $correct_info=UserCorrectService::getUserCorrectDetail($uid); 
        if($correct_info){
            $model=UserCorrectService::findOne(['uid'=>$uid]);
            $model->status=$status;
            $model->save();
            $data['status']=$status;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
        }else{
            $data['message']='user not find';
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL,$data);
        }
    }
}
