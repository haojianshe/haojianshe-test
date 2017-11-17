<?php
namespace mis\controllers\video;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoResourceService;


use mis\models\UploadForm;
use yii\web\UploadedFile;

/**
 * 接收视频工作流转化成功,
 * 代码来自阿里云消息服务订阅消息demo
 * demo里的代码有错误，与阿里云帮助文档不相符，所有需要参加验证的header项都需要把名称改成小写才能通过校验
 */
class NoticeAction extends MBaseAction
{ 
    public function run()
    {	
    	//1.获取header中需要参加校验的信息，进行校验
    	$tmpHeaders = array();
    	$headers = $this->getallheaders();
    	foreach ($headers as $key => $value)
    	{
    		if (0 === strpos($key, 'x-mns-'))
    		{
    			$tmpHeaders[$key] = $value;
    		}
    	}
    	ksort($tmpHeaders);
    	$canonicalizedMNSHeaders = implode("\n", array_map(function ($v, $k) { return $k . ":" . $v; }, $tmpHeaders, array_keys($tmpHeaders)));
    	
    	$method = $_SERVER['REQUEST_METHOD'];
    	$canonicalizedResource = $_SERVER['REQUEST_URI'];
    	$contentMd5 = '';
    	if (array_key_exists('Content-MD5', $headers))
    	{
    		$contentMd5 = $headers['Content-MD5'];
    	}
    	else if (array_key_exists('content-md5', $headers))
    	{
    		$contentMd5 = $headers['content-md5'];
    	}
    	$contentType = '';
    	if (array_key_exists('content-type', $headers))
    	{
    		$contentType = $headers['content-type'];
    	}
    	$date = $headers['date'];
    	//由上面获取到的header项组成参加校验的数据
    	$stringToSign = strtoupper($method) . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $canonicalizedMNSHeaders . "\n" . $canonicalizedResource;
    	
    	$publicKeyURL = base64_decode($headers['x-mns-signing-cert-url']);
    	$publicKey = $this->get_by_url($publicKeyURL);
    	$signature = $headers['authorization'];    	
    	$pass = $this->verify($stringToSign, $signature, $publicKey);
    	if (!$pass)
    	{
    		http_response_code(400);
    		return;
    	}    	
    	//2.解析消息
    	$content = file_get_contents("php://input");
    	if (!empty($contentMd5) && $contentMd5 != base64_encode(md5($content)))
    	{
    		http_response_code(401);
    		return;
    	}
    	//3. 处理消息
    	$this->writeLog($content);
        $this->updateDataTable($content);
    	http_response_code(200);
    	die('');
    }
    private  function updateDataTable($content){
        $message_str=json_decode($content)->Message;
        $Message_obj=json_decode($message_str);
        switch ($Message_obj->Name) {
            case 'activityStart':
                if ($Message_obj->State=="Success") {
                   $filename_full=$Message_obj->MediaWorkflowExecution->Input->InputFile->Object;
                   $filename_fomat=substr(strrchr($Message_obj->MediaWorkflowExecution->Input->InputFile->Object, "/"),1);
                   $filename=strstr($filename_fomat,'.',true);  
                   $runid=$Message_obj->MediaWorkflowExecution->RunId;
                   //add by ljq,mis里写文件名可能没有start事件快，导致某些视频转化后没有办法更新成功,目前采用最多等十秒的策略尝试
        		   $this->updateRunid($filename, $runid);
                }
                break;
            case 'Act-Report':
                if($Message_obj->State=="Success"){
                    $runid=$Message_obj->MediaWorkflowExecution->RunId;
                    VideoResourceService::updateM3u8UrlByRunid($runid);
                }
                break;
            default:
                # code...
                break;
        }
    }
    
    /**
     * add by ljq
     * 解决并发访问，开始转码事件比mis存文件名事件先到达的问题
     * 最多等待10秒
     */
    private function updateRunid($filename,$runid){
    	$iscontinue = true;
    	$i = 0;
    	while ($iscontinue){
    		$ret = VideoResourceService::updateRunidByFileName($filename,$runid);
    		if($ret){
    			//更新成功后,直接返回
    			return true;
    		}
    		//更新失败后等待1秒钟重试
    		sleep(1);
    		$i=$i+1;
    		//记日志
    		$this->writeLog('第'.$i.'次尝试更新runid:'.$runid.'--'.$filename);
    		if($ret || $i>10){
    			$iscontinue = false;
    		}    	
    	}
    	return false;    	
    }
    
    /**
     * 方法记日志
     * @param unknown $msg
     */
    private function writeLog($msg){
    	$logfile = __DIR__ . '/../../runtime/logs/mp4tom3u8_log.txt';
    	//日志自动增加时间和换行
    	$msg = date('Y-m-d H:i:s',time()) .'---' . $msg . PHP_EOL;
    	file_put_contents($logfile,$msg, FILE_APPEND);
    }
    
    private function get_by_url($url)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    
    	$output = curl_exec($ch);
    
    	curl_close($ch);
    
    	return $output;
    }
    
    private function verify($data, $signature, $pubKey)
    {    	
    	$res = openssl_get_publickey($pubKey);
    	$result = (bool) openssl_verify($data, base64_decode($signature), $res);
    	openssl_free_key($res);
    	return $result;
    }
    
    private function getallheaders()
    {
    	$headers = array();
    	foreach ($_SERVER as $name => $value)
    	{
    		if (substr($name, 0, 5) == 'HTTP_')
    		{
    			$headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
    		}
    	}
    	return $headers;
    }
}
