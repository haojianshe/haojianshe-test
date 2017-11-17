<?php

namespace common\lib\myb\enumcommon;

/**
 * 系统消息类型的枚举类
 * 目前在api里使用到的只有评论、回复评论、关注、赞、私信，其他的暂时未使用
 * @author Administrator
 *
 */
final class SysMsgTypeEnum {

    //@功能
    const AT = 0;
    //发私信
    const MAIL = 1;
    //评论
    const COMMENT = 2;
    //回复评论
    const COMMENT_REPLY = 3;
    //评论删除
    const COMMENT_DELETE = 4;
    //关注
    const FOLLOW = 5;
    //赞
    const PRAISE = 6;
    //发新帖
    const NEW_TWEET = 7;
    const NEW_FRIEND = 8;
    const MIS_AUTHENTED = 9;
    //给帖子打tag
    const TAG = 10;
    //发出求批改请求
    const CORRECT_SUBMIT = 11;
    //批改完成
    const CORRECT_FINISH = 12;
    //老师拒批
    const CORRECT_REFUSE = 15;
    //老师转作品
    const CORRECT_CHANGE = 16;
    //帖子转素材
    const TWEET_TO_MATERIAL = 13;
    //推荐步骤图
    const TWEET_REC_LESSON = 14;
    //求批该进入排行榜
    const CORRECT_RANK = 17;
    //打赏通知类型
    const CORRECT_TEACHER_GIFT = 18;


    /* -------------金币相关  start ------------- */
    //用户注册得到金币数
    const NEW_USER_GET_COINS = 30;
    //用户登录得到金币数
    const LOGIN_GET_COINS = 10;
    //用户发帖奖励金币数
    const NEW_TWEET_GET_COINS = 10;
    //用户评论奖励金币数
    const NEW_CMMENT_GET_COINS = 3;
    //用户每天最多发帖次数
    const DAY_TWEET_MAX_COUNT = 10;
    //用户每天最多评论次数
    const DAY_COMMENT_MAX_COUNT = 20;
    //登录
    const ADDCOIN_LOGIN_TYPE = 1;
    //发帖
    const ADDCOIN_TWEET_TYPE = 2;
    //评论
    const ADDCOIN_COMMENT_TYPE = 3;

    /* -------------金币相关 end ------------- */
}
