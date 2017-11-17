<?php
namespace console\lib\EnumCommon;

/**
 * 系统消息类型的枚举类
 * 目前在api里使用到的只有评论、回复评论、关注、赞、私信，其他的暂时未使用
 * @author Administrator
 *
 */
final class SysMsgTypeEnum
{    
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
}