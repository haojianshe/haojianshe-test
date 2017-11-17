<?php
namespace common\service;

use Yii;
use yii\base\Object;
/**
 * 推送服务
 * require引入的类前边必须加\,否则报not found
 */

class SmsService extends Object
{

    static $password=707142;
    static $account='meiyuanbang';
    /**
    *
    *  畅桌官方post方法
    *  @author ihziluoh 
    */
    public static function Post($data, $target) {
        $url_info = parse_url($target);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $data;

        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        return $gets;
    }

   /** 
    *   通过手机发送短信方法
    *@author ihziluoh
    *
    *$this->load->library('sendsms');
    *$this->sendsms->sendMobileSms();
    **/

    public static function SendMobileSms($mobile,$content,$return_type=1,$sendtime=NULL){
        $target = "http://sms.chanzor.com:8001/sms.aspx";
        //替换成自己的测试账号,参数顺序和wenservice对应
        $post_data = "action=send&userid=&account=".self::$account."&password=".self::$password."&mobile=".$mobile."&content=".rawurlencode($content);
        if($sendtime){
            $post_data.="&sendTime=".date("Y-m-d H:i:s", $sendtime); 
        }

        //$binarydata = pack("A", $post_data);
        $gets = self::Post($post_data, $target);
        $start=strpos($gets,"<?xml");
        $data=substr($gets,$start);
        $xml=simplexml_load_string($data);
        //返回所有
        if($return_type!=1){
            return $xml;
        }

        //只返回状态
        if($xml->returnstatus == 'Success'){
            $res['identifier']=$xml->taskID;
            $res['status']=1;
            $res['valid']=1;
            return $res;
        }else{
            return false;
        }
    }

}