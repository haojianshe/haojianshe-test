<?php
namespace api\controllers\Version;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 得到版本信息
 */
class GetAction extends ApiBaseAction
{   
    public function run()
    {  
        $devicetype= $this->requestParam('devicetype');
        if (isset($devicetype) && $devicetype == 'ios') {
        	//如果ios未发布，则不提示更新信息
        	if(Yii::$app->params['ios_publish']=='0'){
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}
            //审核结束后给客户端提供版本和升级信息
            $data['version']=Yii::$app->params['ios_version'];
            $data['isforce']=Yii::$app->params['ios_isforce'];
            $message[]= "直播课列表优化增加分类";
            $message[]= "课程视频页面排版优化，直播页面排版优化";
            $message[]= "收藏优化-增加搜索分类";
            $message[]= "跟着画详情页面改版，增加精彩推送";            
            $message[]= "修复一些bug";            
            $data['message']=$message;              
        }else {
            $data['version']=Yii::$app->params['android_version'];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
