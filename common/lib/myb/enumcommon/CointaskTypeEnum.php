<?php
namespace common\lib\myb\enumcommon;

/**
 * 金币任务类型
 * @author Administrator
 *
 */
final class CointaskTypeEnum
{
	//新用户首次注册
	const FIRST_REGISTER = 101;
	//第三方用户注册
	const THIRDPART_REGISTER = 102;
	//账号关联
	const USER_UNION = 103;
	//完善注册信息
	const FINISH_REGIST_INFO = 104;
	//首次完善个人资料
	const FINISH_USERINFO = 105;
	
	//学生超三次求批改
	const MORE_CORRECT = 201;
	//加入小组 一次性
	const ADD_TEAM = 202;
	//学生上传作品
	const USER_TWEET = 203;
	//进入排行榜
	const RANK_LIST = 204;
	//连续5天求批改
	const CONTINUE_CORRECT = 205;
	
	//老师上传作品
	const TEACHER_TWEET = 301;
	
	//分享
	const SHARE = 401;
	//意见反馈
	const ADVISE = 402;
	//评论
	const COMMENT = 403;
	//关注他人
	const FOLLOW = 404;
}