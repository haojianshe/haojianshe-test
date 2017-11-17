<?php
namespace common\service;

use Yii;
use yii\base\Object;
/**
 * 推送服务
 * require引入的类前边必须加\,否则报not found
 */
require_once __DIR__ . '/../../vendor/xingeapp/XingeApp.php';

class XingeAppService extends Object
{
    private static  $android_access_id = 2100130351;
    private static  $android_secret_key = "b45df69e12af7a432b911073f90f0303";
    private static  $ios_access_id = 2200130352;
    private static  $ios_secret_key = "2a09043b6e1bc53c8eab009c3733aa18";
    //ios 测试环境IOSENV_DEV  IOSENV_PROD正式环境
    function __construct() {
         parent::__construct();
    }
    /**
     * 通过devicetoken 给单个android用户发送通知 
     * @param unknown $params
     * @return Ambigous <multitype:number string , mixed>
     */
    static function push_android_by_devicetoken($params){
        $push = new \XingeApp(self::$android_access_id, self::$android_secret_key);
        $mess = new \Message();
        $mess->setType(\Message::TYPE_MESSAGE);
        $mess->setTitle($params['title']);
        $mess->setContent($params['content']);
        $mess->setSendTime($params['sendTime']);
        $mess->setExpireTime(86400);
        #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知，呼吸灯
        $style = new \Style(0,1,1,0,1);
        $action = new \ClickAction();  
        #打开url需要用户确认    
        $mess->setStyle($style);
        $mess->setCustom($params['custom']);
        $acceptTime = new \TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime);
        $ret = $push->PushSingleDevice($params['dev_token'], $mess);
        return($ret);
    }

    /**
     * 通过devicetoken 给单个ios用户发送通知 
     * @param  [type] $title     [description]
     * @param  [type] $content   [description]
     * @param  [type] $dev_token [description]
     * @param  [type] $page_url  [description]
     * @param  [type] $sendTime  [description]
     * @return [type]            [description]
     */
    static function push_ios_by_devicetoken($params){
        $push = new \XingeApp(self::$ios_access_id, self::$ios_secret_key);
        $mess = new \MessageIOS();
        $mess->setExpireTime(86400);
        $mess->setSendTime($params['sendTime']);
        $mess->setAlert($params['content']);
        //桌面图标右上角的新消息数字
        if(isset($params['badge'])){
        	$mess->setBadge($params['badge']);
        }
        //接收到消息时的声音
        if(isset($params['sound'])){
        	$mess->setSound($params['sound']);
        }        
        $mess->setCustom($params['custom']);
        $acceptTime = new \TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime);
        //ios推送设置测试环境和生产环境
        if(Yii::$app->params['iospushenv'] == 'dev'){
        	$iosenv = \XingeApp::IOSENV_DEV;
        }
        else{
        	$iosenv = \XingeApp::IOSENV_PROD;
        }
        //ios暂时改成发两次通知，支持测试和正式环境
        //$ret = $push->PushSingleDevice($params['dev_token'], $mess, $iosenv); //测试环境IOSENV_DEV  IOSENV_PROD正式环境
        $ret = $push->PushSingleDevice($params['dev_token'], $mess, \XingeApp::IOSENV_PROD);
        $ret1 = $push->PushSingleDevice($params['dev_token'], $mess, \XingeApp::IOSENV_DEV); 
        return $ret;
    }

    /**
     * 通过devicetoken 给单个ios用户发送通知 
     * @param  [type] $title    [description]
     * @param  [type] $content  [description]
     * @param  [type] $page_url [description]
     * @param  [type] $sendTime [description]
     * @return [type]           [description]
     */
    static function push_android_alldevice($title,$content,$page_url,$sendTime){
        $push = new \XingeApp(self::$android_access_id, self::$android_secret_key);
        $mess = new \Message();
        $mess->setType(\Message::TYPE_MESSAGE);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setSendTime($sendTime);
        $mess->setExpireTime(86400);

        #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
        $style = new \Style(0,1,1,0,1);
        $action = new \ClickAction();      
        $j=array('j'=>$page_url);
        $custom = array('t'=>'1', 'p'=>$j);
        $mess->setStyle($style);
        $mess->setCustom($custom);
        $acceptTime1 = new \TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime1);
        $ret = $push->PushAllDevices(0, $mess);
        return($ret);
    }
    /**
     * 通过devicetoken 给单个ios用户发送通知 
     * @param  [type] $title    [description]
     * @param  [type] $content  [description]
     * @param  [type] $page_url [description]
     * @param  [type] $sendTime [description]
     * @return [type]           [description]
     */
    static function push_ios_alldevice($title,$content,$page_url,$sendTime){
        $push = new \XingeApp(self::$ios_access_id, self::$ios_secret_key);
        $mess = new \MessageIOS();
        $mess->setExpireTime(86400);
        $mess->setSendTime($sendTime);
        $mess->setAlert($title);
        $mess->setBadge(1);
        $j=array('j'=>$page_url);
        $custom = array('t'=>'1', 'p'=>$j);
        $mess->setCustom($custom);
        $acceptTime = new \TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime);
        $aps = array(           
            "alert" => $title,
            "badge" => 1
        	//ios加content-available代表静默消息，ios会收不到
            //"content-available" => 1
            );  
        //ios推送设置测试环境和生产环境
        if(Yii::$app->params['iospushenv'] == 'dev'){
            $iosenv = \XingeApp::IOSENV_DEV;
        }
        else{
            $iosenv = \XingeApp::IOSENV_PROD;
        }
        $raw = array("aps" => $aps,'t'=>'1', 'p'=>$j);
        $mess->setRaw(json_encode($raw));
        $ret = $push->PushAllDevices(0, $mess, $iosenv);
        return $ret;
    }
    /**
     * 群发推送
     * @param  [type] $device_type [description]
     * @param  [type] $title       [description]
     * @param  [type] $content     [description]
     * @param  [type] $page_url    [description]
     * @param  string $sendTime    [description]
     * @return [type]              [description]
     */
    static function push_device_by_type($device_type,$title,$content,$page_url,$sendTime="2030-03-13 12:00:00"){
        if(empty($sendTime)){
            $sendTime=date('y-m-d h:i:s',time()); 
        }
        
        switch ($device_type) {
            case '1': //发送android设备
                    $push_idarr = XingeAppService::push_android_alldevice($title,$content,$page_url,$sendTime);
                    if($push_idarr['ret_code']==0){
                        $result["android_push_id"]= $push_idarr['result']['push_id'];
                        $result["ios_push_id"]=-1;
                        $result["result"]='';
                    }else{
                        $result["android_push_id"]=-1;
                        $result["ios_push_id"]=-1;
                        $result["result"]=json_encode($push_idarr);
                    }
                break;
            case '2'://发送ios设备
                    //$page_url=urldecode($page_url);
                    $push_idarr = XingeAppService::push_ios_alldevice($title,$content,$page_url,$sendTime);
                    if($push_idarr['ret_code']==0){
                        $result["ios_push_id"]= $push_idarr['result']['push_id'];
                         $result["android_push_id"]=-1;
                        $result["result"]='';
                    }else{
                        $result["ios_push_id"]=-1;
                         $result["android_push_id"]=-1;
                        $result["result"]=json_encode($push_idarr);
                    }
                break;
            case '3'://发送 android 和ios 设备
                    $push_android_idarr=XingeAppService::push_android_alldevice($title,$content,$page_url,$sendTime);
                    if($push_android_idarr['ret_code']==0){
                        $result["android_push_id"]= $push_android_idarr['result']['push_id'];
                        $result["ios_push_id"]=-1;
                         $result["result"]='';
                    }else{
                        $result["android_push_id"]=-1;
                        $result["ios_push_id"]=-1;
                        $result["result"]=json_encode($push_android_idarr);
                        //返回错误
                        return $result;
                    }
                    $push_ios_idarr=XingeAppService::push_ios_alldevice($title,$content,$page_url,$sendTime);
                    if($push_ios_idarr['ret_code']==0){
                        $result["ios_push_id"]= $push_ios_idarr['result']['push_id'];
                        $result["result"]='';
                    }else{
                        $result["ios_push_id"]=-1;
                        $result["result"]=json_encode($push_ios_idarr);
                        //如果ios设备发送失败 取消android 设备发送并返回错误
                        self::cancel_android_push($result["android_push_id"]);
                        return $result;
                    }
                break;
            default:
                break;
        }
        return $result;
    }
    /**
     * 给单个设备推送
     * @param  [type] $device_type [description]
     * @param  [type] $title       [description]
     * @param  [type] $content     [description]
     * @param  [type] $dev_token   [description]
     * @param  [type] $page_url    [description]
     * @param  [type] $sendTime    [description]
     * @return [type]              [description]
     */
    static function push_by_device_token($device_type,$title,$content,$dev_token,$page_url,$sendTime){
        if(empty($sendTime)){
            $sendTime=date('y-m-d h:i:s',time()); 
        }
        $params['title']=$title;
        $params['content']=$content;
        $params['dev_token']=$dev_token;
        $params['sendTime']=$sendTime;
        $params['custom']['t'] = 1;
        $params['custom']['p']['j'] = $page_url;
        switch ($device_type) {
            case '1': //发送android设备 push_android_by_devicetoken($title,$content,$dev_token,$page_url,$sendTime)
                    $push_idarr = XingeAppService::push_android_by_devicetoken($params);
                    $result["result"]=json_encode($push_idarr);
                break;
            case '2'://发送ios设备
                    $params['badge']=1;
                    //$params['custom']['p']['j'] = urldecode($page_url);
                    $push_idarr = XingeAppService::push_ios_by_devicetoken($params);
                    $result["result"]=json_encode($push_idarr);
                break;
        }
        return $result;
    }
    
    /**
     * 创建安卓群发批量消息
     * @param unknown $params
     * @return 返回群发消息的pushid
     */
    static function CreateMultipush_andriod($params){
    	$push = new \XingeApp(self::$android_access_id, self::$android_secret_key);
    	$mess = new \Message();
    	$mess->setType(\Message::TYPE_MESSAGE);
    	$mess->setTitle($params['title']);
    	$mess->setContent($params['content']);
    	$mess->setSendTime($params['sendTime']);
    	$mess->setExpireTime(86400);
    	#含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知，呼吸灯
    	$style = new \Style(0,1,1,0,1);
    	$action = new \ClickAction();
    	#打开url需要用户确认
    	$mess->setStyle($style);
    	$mess->setCustom($params['custom']);
    	$acceptTime = new \TimeInterval(0, 0, 23, 59);
    	$mess->addAcceptTime($acceptTime);
    	$ret = $push->CreateMultipush($mess);
    	return($ret);
    }
    
    /**
     * 创建ios群发批量消息
     * @param unknown $params
     * @return 返回群发消息的pushid
     */
    static function CreateMultipush_ios($params){
    	$push = new \XingeApp(self::$ios_access_id, self::$ios_secret_key);
    	$mess = new \MessageIOS();
    	$mess->setExpireTime(86400);
    	$mess->setSendTime($params['sendTime']);
    	$mess->setAlert($params['content']);
    	//桌面图标右上角的新消息数字
    	if(isset($params['badge'])){
    		$mess->setBadge($params['badge']);
    	}
    	//接收到消息时的声音
    	if(isset($params['sound'])){
    		$mess->setSound($params['sound']);
    	}
    	$mess->setCustom($params['custom']);
    	$acceptTime = new \TimeInterval(0, 0, 23, 59);
    	$mess->addAcceptTime($acceptTime);
    	//ios推送设置测试环境和生产环境
    	if(Yii::$app->params['iospushenv'] == 'dev'){
    		$iosenv = \XingeApp::IOSENV_DEV;
    	}
    	else{
    		$iosenv = \XingeApp::IOSENV_PROD;
    	}
    	
    	$ret = $push->CreateMultipush($mess,$iosenv);
    	return $ret;
    }
    
    /**
     * 群发推送
     * @param unknown $pushId 上一步建立的群发消息id
     * @param unknown $deviceList  要接收推送的token数组
     * @param unknown $isandriod true 代表andriod  false 代表ios
     * @return Ambigous <multitype:number string , mixed>
     */
    static function pushByTokens($pushId, $deviceList,$isandriod){
    	if($isandriod){
    		$push = new \XingeApp(self::$android_access_id, self::$android_secret_key);
    	}
    	else{
    		$push = new \XingeApp(self::$ios_access_id, self::$ios_secret_key);
    	}    	
    	$ret = $push->PushDeviceListMultiple($pushId, $deviceList);
    	return $ret;
    }  
    
    /**
     * android 撤销推送
     * @param  [type] $pushid [description]
     * @return [type]         [description]
     */
    static function cancel_android_push($pushid){
        $push = new \XingeApp(self::$android_access_id, self::$android_secret_key);
        $ret = $push->CancelTimingPush($pushid);
        return $ret;
    }
    /**
     * ios撤销推送
     * @param  [type] $pushid [description]
     * @return [type]         [description]
     */
    static function cancel_ios_push($pushid){
        $push = new \XingeApp(self::$ios_access_id, self::$ios_secret_key);
        $ret = $push->CancelTimingPush($pushid);
        return $ret;
    }
}