<?php
namespace mis\controllers\msg;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\UserTokenService;

/**
 * 检查发件人、收件人、是否正确
 * 发送私信
 */
class AjaxCheckAction extends MBaseAction
{
	public $resource_id = 'operation_msg';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	//区分发信人还是收信人
    	if($request->isPost){
    		if(!$request->post('ajaxtype')){
    			die('缺少参数');
    		}
    		$ajaxtype = $request->post('ajaxtype');
    		//检查发件人
    		if($ajaxtype == 'sendercheck'){
    			if(!$request->post('sname')){
    				die('缺少参数');
    			}
    			$sname = urldecode($request->post('sname'));
    			return $this->senderCheck($sname);
    		}
    		//发送私信
    		if($ajaxtype == 'send'){
    			if(!$request->post('uid') || !$request->post('receivername') || !$request->post('token') || !$request->post('msgcontent')){
    				die('缺少参数');
    			}
    			$mtype = 0;
    			$uid = $request->post('uid');
    			$msgcontent = urldecode($request->post('msgcontent'));
    			$token = $request->post('token');
    			$receivername = urldecode($request->post('receivername'));
    			$ret = UserService::findOne(['sname' => $receivername]);
    			if(!$ret){
    				return $this->controller->outputMessage(['errno'=>1,'msg'=>"用户 {$receivername}不存在"]);
    			}
    			return $this->sendmsg($uid, $ret->uid, $mtype, $msgcontent,$token);
    		}
    	}
    }
    
    /**
     * 发送私信
     * @param unknown $sname
     * @param unknown $receiver
     * @param unknown $msgcontent
     */
    private function sendmsg($uid,$to_uid,$mtype,$msgcontent,$token){
    	$data = [
    			'mtype' => $mtype,
    			'uid' => $uid,
    			'to_uid' => $to_uid,
    			'content' => $msgcontent,
    			'token' => $token,
    	];
    	$url = Yii::$app->params['apisiteurl'].'message/newmsg';    	    	
    	$ret = $this->curl($url,$data);
    	if($ret == false){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'意外错误']);
    	}
    	else{
    		$ret = json_decode($ret,true);
    		if($ret['errno']==0){
    			return $this->controller->outputMessage(['errno'=>0,'msg'=>'']);
    		}
    		else{
    			return $this->controller->outputMessage(['errno'=>1,'msg'=>'错误代码:'.$ret['errno']]);
    		}    		
    	}
    }
    
    /**
     * 检查发信人是否合法
     */
    private function senderCheck($sname){
    	//判断用户是否存在
    	$ret = UserService::findOne(['sname' => $sname]);
    	if(!$ret){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户不存在']);
    	}
    	$uid = $ret['uid'];
		//获取用户token
    	$ret =  UserTokenService::getByUid($uid);
    	if(!$ret){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户token不存在，请使用发信人账号在app端先进行登录']);
    	}
    	if($ret['invalid_time']<=time()){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户token已过期，请使用发信人账号在app端先进行登录']);
    	}
    	return $this->controller->outputMessage(['errno'=>0,'uid'=>$uid,'token'=>$ret['hash_key']]); 
    }
    
    /**
     * curl操作
     * @param unknown $url
     * @return boolean|mixed
     */
    private function curl($url,$data) {
    $ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt ( $ch, CURLOPT_POST, 1 );
    	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
    	$output = curl_exec($ch);
    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    	if (200 != $http_code) {
    		return false;
    	} else {
    		return $output;
    	}
    }
}
