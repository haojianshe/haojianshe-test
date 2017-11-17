<?php

namespace common\service;

use Yii;
use yii\base\Object;

/**
 * 微信方法类
 */
class WeiXinService extends Object {

    /**
     * 获取jssdk 配置参数
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function get_jssdk_param($s_url) {
        //兼容服务器在slb层面支持https的功能，在php处只能获得http地址，会是的jssdk签名错误
        if (strpos($s_url, 'http://m.meiyuanbang.com') !== false) {
            $s_url = str_replace("http://m.meiyuanbang.com", "https://m.meiyuanbang.com", $s_url);
        }
        $timestamp = time();
        $wxnonceStr = "defwt123fyhty";
        $wxticket = self::wx_get_jsapi_ticket();
        $wxOri = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s", $wxticket, $wxnonceStr, $timestamp, $s_url);
        $wxSha1 = sha1($wxOri);
        $data['timestamp'] = $timestamp;
        $data['nonceStr'] = $wxnonceStr;
        $data['signature'] = $wxSha1;
        $data['appId'] = Yii::$app->params['wx_appid'];
        return $data;
    }

    //判断在微信浏览器中
    public static function is_weixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 获取微信token 缓存redis
     * @return [type] [description]
     */
    public static function wx_get_token() {

        $redis = Yii::$app->cache;
        $token = $redis->getValue("access_token");
        if (!$token) {
            $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . Yii::$app->params['wx_appid'] . '&secret=' . Yii::$app->params['wx_secret']);
            $res = json_decode($res, true);
            $token = $res['access_token'];
            // 注意：这里需要将获取到的token缓存起来（或写到数据库中）
            // 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
            // 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
            // 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
            // 就可以避免token失效。
            // S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
            $redis->setValue("access_token", $token, 5400);
            //Yii::app()->session['access_token']=$token;
        }
        return $token;
    }

    /**
     * 获取微信ticket 缓存本地
     * @return [type] [description]
     */
    public static function wx_get_jsapi_ticket() {
        $redis = Yii::$app->cache;
        $wx_ticket = $redis->getValue("wx_ticket");
        $token = $redis->getValue("access_token");
        if (!$wx_ticket) {
            if (empty($token)) {
                $token = self::wx_get_token();
            }
            if (empty($token)) {
                //log("get access token error.");
                break;
            }
            $url2 = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $token);
            $res = file_get_contents($url2);
            $res = json_decode($res, true);
            $wx_ticket = $res['ticket'];
            $redis->setValue("wx_ticket", $wx_ticket, 5400);
        }
        return $wx_ticket;
    }

}
