<?php
namespace api\controllers\Version;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 是否显示第三发方登录，用于苹果审核
 * v3.0.4增加支付类型，paytype=0表示苹果支付  1表示微信支付宝
 */
class ThirdLoginDisplayAction extends ApiBaseAction
{   
    public function run()
    {
    	//设备号
        $devicetype= $this->requestParam('devicetype');
        //版本号
        $version= $this->requestParam('version');
        //本地配置文件版本号
        $localversion = Yii::$app->params['ios_version'];
        //当前是否为已上线状态
        $ispublish = Yii::$app->params['ios_publish'];
        //v3.0.4增加支付类型，目前paytype全都使用苹果支付，以后除审核版本外，可能会改为正常支付
        $data['paytype'] = Yii::$app->params['ios_paytype'];
        if($ispublish=='0' && $localversion==$version){
        	//如果是发给苹果审核的版本，只能内购
        	$data['paytype'] = 0;
        }
        //已上线则显示第三方登录
        if($ispublish=='1'){
        	$data['isdisplay'] = '1';
        	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        //未上线
        if($version == $localversion){
        	//正在审核的版本号隐藏第三方登录
        	$data['isdisplay'] = '0';
        }
        else{
        	//老版本显示第三方登录
        	$data['isdisplay'] = '1';
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
