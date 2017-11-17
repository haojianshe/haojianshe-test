<?php

namespace api\lib\enumcommon;

/**
 * api返回code列表
 * @author Administrator
 *
 */
final class ReturnCodeEnum {

    //成功
    const STATUS_OK = 0;
    //请求错误
    const STATUS_ERR_REQUEST = 1;
    //响应错误
    const STATUS_ERR_RESPONSE = 2;
    //写数据库错误
    const MYSQL_ERR_INSERT = 103;
    //错误的device token
    const ERR_DEVICE_TOKEN = -1;
    //把token相关的几个错误合并为1个
    const ERR_TOKEN = 10004;
    //3.1.1添加,多设备登录导致的token失效 
    const ERR_TOKEN_REPEAT = 10006;
    //非法用户
    const ERR_USER_ILLEGAL = 9999;
    //------用户相关
    //密码错误
    const USER_ERR_PASS = 802;
    //第三方注册账号未绑定手机号
    const ERR_USER_NO_BIND = 809;
    //用户已注册
    const USER_EXIST = 803;
    //手机已绑定第三方账号
    const ERR_USER_MOBILE_BIND = 810;
    //第三方账号已绑定
    const ERR_USER_OAUTH_BIND = 811;
    //用户不存在
    const USER_NOT_EXIST = 807;
    //用户名已存在
    const USER_SNAME_EXIST = 805;
    //短信相关
    //验证码错误
    const ERR_SMS_VERIFYCODE_ILLEGAL = 10013;
    //验证码超时
    const ERR_SMS_VERIFYCODE_TIMEOUT = 10014;

}
