<?php
namespace console\lib\EnumCommon;

/**
 * 推送类型
 * @author Administrator
 *
 */
final class PushMessageTypeEnum
{
	//跳转推送,会在手机通知栏出现
	const NOTIFY = 1;
	//小红点推送,系统消息
	const NOTIFYRED = 2;
	//小红点推送,私信
	const EMAILRED = 3;
}