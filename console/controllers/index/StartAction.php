<?php

namespace console\controllers\index;

use Yii;
use yii\base\Action;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\lib\myb\enumcommon\PushMessageTypeEnum;
use console\service\SystemMessageService;
use console\service\UserDetailService;
use console\service\UserPushService;
use common\service\XingeAppService;
use console\service\CorrectService;

/**
 * 启动pushservice守护进程
 */
class StartAction extends Action {

    public function run() {
        //获取要启动的进程数offhubtask
        $processnum = Yii::$app->params['processnum'];
        //强制休眠数，当进程处理到一定数量的数据时强制休眠，避免缓存数据太多卡系统
        $sleepnum = Yii::$app->params['sleepnum'];
        //存储pid的文件路径
        $pidfile = __DIR__ . '/../../push.pid';
        //清除文件状态缓存
        clearstatcache();
        if (!file_exists($pidfile)) {
            //没有标志文件，创建文件
            file_put_contents($pidfile, '', LOCK_EX);
            //改变文件的权限，否则file_exists函数无法判断pid是否存在
            chmod($pidfile, 0777);
        } else {
            //有标志文件，判断是否正在运行中
            die('程序正在运行中,请先关闭' . PHP_EOL);
        }
        for ($i = 0; $i < $processnum; $i++) {
            //启动子进程
            $pid = pcntl_fork();
            if ($pid == -1) {
                //启动子进程错误
                die('could not fork');
            } else if ($pid > 0) {
                //父进程代码逻辑,pid>0,记录子进程的pid,用于stop时kill
                file_put_contents($pidfile, $pid . ',', FILE_APPEND | LOCK_EX);
            } else {
                //子进程代码进入此逻辑pid=0
                echo '启动进程-' . getmypid() . PHP_EOL;
                //计数器，当处理到一定数量的推送消息时，休眠1秒，避免占用太多cpu资源
                $num = 0;
                $redis = Yii::$app->cachequeue;
                $rediskey = 'offhubtask';
                while (true) {
                    try {
                        //从缓存取数据
                        $params = $redis->rpop($rediskey);
                        //没有取到数据则sleep1秒
                        if (!$params) {
                            sleep(1);
                            $num = 0;
                            continue;
                        }
                        //数据处理,目前有发系统消息和消除小红点两个操作，根据任务的不同进行不同处理
                        $params = json_decode($params, true);
                        switch ($params['tasktype']) {
                            case 'sysmsg':
                                //即时推送消息，判断消息写入时间，如果超过10分钟就不在发送了
                                $timespan = time() - $params['tasktctime'];
                                if ($timespan > 600) {
                                    continue;
                                }
                                $this->push($params);
                                break;
                            case 'couponmsg':
                                //课程卷消息
                                $this->couponHandle($params);
                                break;
                            case 'groupbuymsg':
                                //团购消息
                                $this->groupbuyHandle($params);
                                break;
                            case 'clearred':
                                //消除小红点
                                $this->clearRedHandle($params);
                                break;
                        }
                        //守护进程时，关闭redis连接，否则会引起服务器端连接数过高
                        $redis->redis->close();
                        //执行到最大处理数休眠一秒，避免卡系统
                        $num++;
                        if ($num > $sleepnum) {
                            sleep(1);
                            $num = 0;
                        }
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
        }
    }

    /**
     * 系统消息离线任务的处理逻辑,区分推送类型转到相应的处理逻辑
     * 原系统会检查发送消息的频率，此版去掉，因为可能有一些来自mis的批量请求
     * custom的t参数是消息类型 1:推送消息 2:系统消息小红点 3:私信小红点
     * p包括j uid等参数,uid在发私信小红点消息时需要，系统消息小红点的时候没有,j在通知的时候需要,不同类型内容，现在只有私信和系统消息
     */
    private function push($params) {
        //(1)获取最近使用的设备token
        $tokenmodel = UserPushService::getByUid($params['to_uid']);
        if (!$tokenmodel) {
            return;
        }
        //(2)根据消息类型转到相应的推送处理程序
        switch ($params['action_type']) {
            case SysMsgTypeEnum::MAIL :
                //私信
                $this->pMsgHandle($params, $tokenmodel);
                break;
            case SysMsgTypeEnum::CORRECT_SUBMIT :
                //提交批改
                $this->correctHandle($params, $tokenmodel);
                break;
            case SysMsgTypeEnum::CORRECT_FINISH :
                //完成批改
                $this->correctHandle($params, $tokenmodel);
                break;
            case SysMsgTypeEnum::CORRECT_REFUSE :
                //拒绝批改
                $this->correctHandle($params, $tokenmodel);
                break;
            default :
                //系统消息,包括点赞、评论 关注等
                $this->sysMsgHandle($params, $tokenmodel);
                break;
        }
    }

    /**
     * 处理私信推送
     * @param unknown $params
     * @param unknown $tokenmodel
     */
    private function pMsgHandle($params, $tokenmodel) {
        //(1)推送消息小红点
        $custom['p']['j'] = '';
        //私信需要通知客户端发信人，用于在私信列表上也显示小红点
        $custom['t'] = PushMessageTypeEnum::EMAILRED;
        $custom['p']['uid'] = $params['from_uid'];
        $data = [
            'title' => 'red', //title和content不能为空，否则会发送失败
            'content' => 'red',
            'sendtime' => '', //空表示立刻发送
            'custom' => $custom,
            'dev_token' => $tokenmodel['xg_device_token'],
        ];
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($data);
        } else {
            //ios时必须content为空才不会在通知栏显示
            $data['content'] = '';
            //小红点同时添加badge
            $data['badge'] = 1;
            $ret = XingeAppService::push_ios_by_devicetoken($data);
        }
        //(2)推送跳转消息
        $data = [];
        $custom = [];
        $data['title'] = '美院帮消息';
        $data['sendtime'] = '';
        $data['custom']['t'] = PushMessageTypeEnum::NOTIFY;
        $data['custom']['p']['j'] = 'meiyuan://smsg';
        $data['dev_token'] = $tokenmodel['xg_device_token'];
        $fromname = UserDetailService::getNameByUid($params['from_uid']);
        $data['content'] = $fromname . ' 给你发了一条私信';
        //只有私信需要修改j参数，其他都是系统消息
        $data['custom']['p']['j'] = 'meiyuan://pmsg?uid=' . urlencode($params['from_uid']);
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($data);
        } else {
            //ios顶部通知增加声音
            $data['sound'] = "default";
            $ret = XingeAppService::push_ios_by_devicetoken($data);
        }
        //(3)消息推送后处理缓存，用于在客户端接收不到推送的情况下，主动获取未读消息时使用
        $redis = Yii::$app->cache;
        //私信需要记录从每一个from_uid获得了几条新消息，用于个人中心和私信列表两页的展示
        $rediskey = "ms:pmsg" . $params['to_uid'];
        $redis->zincrby($rediskey, 1, $params['from_uid']);
    }

    /**
     * 处理系统消息推送
     * @param unknown $params
     * @param unknown $tokenmodel
     */
    private function sysMsgHandle($params, $tokenmodel) {
        //(1)写系统消息表
        $model = new SystemMessageService();
        $model->from_uid = $params['from_uid'];
        $model->action_type = $params['action_type'];
        $model->to_uid = $params['to_uid'];
        $model->content_id = $params['content_id'];
        $model->is_read = 0;
        $model->is_del = 0;
        $model->ctime = time();
        $model->utime = $model->ctime;
        //获取发送人和接收人的昵称
        $model->from_name = UserDetailService::getNameByUid($model->from_uid);
        $model->to_name = UserDetailService::getNameByUid($model->to_uid);
        if (!$model->save()) {
            //保存不成功不继续发送小红点
            return;
        }
        //(2)推送消息小红点
        $custom['p']['j'] = '';
        $custom['t'] = PushMessageTypeEnum::NOTIFYRED;
        $data = [
            'title' => 'red', //title和content不能为空，否则会发送失败
            'content' => 'red',
            'sendtime' => '', //空表示立刻发送
            'custom' => $custom,
            'dev_token' => $tokenmodel['xg_device_token'],
        ];
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($data);
        } else {
            //ios时必须content为空才不会在通知栏显示
            $data['content'] = '';
            //小红点同时添加badge
            $data['badge'] = 1;
            $ret = XingeAppService::push_ios_by_devicetoken($data);
        }
        //(3)推送跳转消息
        $data = [];
        $custom = [];
        $data['title'] = '美院帮消息';
        $data['sendtime'] = '';
        $data['custom']['t'] = PushMessageTypeEnum::NOTIFY;
        $data['custom']['p']['j'] = 'meiyuan://smsg';
        $data['dev_token'] = $tokenmodel['xg_device_token'];
        switch ($params['action_type']) {
            case SysMsgTypeEnum::COMMENT :
                $data['content'] = $model->from_name . ' 评论了你的帖子';
                break;
            case SysMsgTypeEnum::COMMENT_REPLY :
                $data['content'] = $model->from_name . ' 回复了你的评论';
                break;
            case SysMsgTypeEnum::FOLLOW :
                $data['content'] = $model->from_name . '  关注了你';
                break;
            case SysMsgTypeEnum::PRAISE :
                $data['content'] = $model->from_name . ' 赞了你';
                break;
            case SysMsgTypeEnum::TAG :
                //打标签
                $data['content'] = $model->from_name . ' 点评了你的作品';
                break;
            case SysMsgTypeEnum::TWEET_TO_MATERIAL :
                //转素材
                $data['content'] = $model->from_name . ' 将你的作品加入了素材库';
                break;
            case SysMsgTypeEnum::TWEET_REC_LESSON :
                //推荐步骤图
                $data['content'] = $model->from_name . ' 为你的作品推荐了步骤图';
                break;
            case SysMsgTypeEnum::CORRECT_CHANGE :
                //批改转作品
                $data['content'] = $model->from_name . ' 将你求批改的画作转为了作品';
                break;
            case SysMsgTypeEnum::CORRECT_RANK :
                //批改转作品
                $data['content'] = '您求批改的作品进入了每日排行榜';
                break;
            case SysMsgTypeEnum::CORRECT_TEACHER_GIFT :
                //老师收到学生的打赏
                $data['content'] = $model->from_name .'送你了'.$params['content_name'];
                break;
        }
        //点赞和关注不在发送跳转推送，只发小红点
        if ($params['action_type'] != SysMsgTypeEnum::FOLLOW && $params['action_type'] != SysMsgTypeEnum::PRAISE) {
            if ($tokenmodel['device_type'] == 1) {
                $ret = XingeAppService::push_android_by_devicetoken($data);
            } else {
                //ios顶部通知增加声音
                $data['sound'] = "default";
                $ret = XingeAppService::push_ios_by_devicetoken($data);
            }
        }
        //(4)消息推送后处理缓存，用于在客户端接收不到推送的情况下，主动获取未读消息时使用
        $redis = Yii::$app->cache;
        //系统消息只需要记录一共有多少条新消息
        $rediskey = "ms:msg";
        $redis->zincrby($rediskey, 1, $params['to_uid']);
    }

    /**
     * 处理批改消息的推送
     * @param unknown $params
     * @param unknown $tokenmodel
     */
    private function correctHandle($params, $tokenmodel) {
        $correctinfo = CorrectService::getDetail($params['correctid']);
        //(1)推送消息小红点(需要显示分类对应小圆点)
        $custom['p']['j'] = 'meiyuan://correctmsg?f_catalog_id=' . $correctinfo['f_catalog_id'];
        $custom['t'] = PushMessageTypeEnum::CORRECTRED;
        $data = [
            'title' => 'red', //title和content不能为空，否则会发送失败
            'content' => 'red',
            'sendtime' => '', //空表示立刻发送
            'custom' => $custom,
            'dev_token' => $tokenmodel['xg_device_token'],
        ];
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($data);
        } else {
            //ios时必须content为空才不会在通知栏显示
            $data['content'] = '';
            //小红点同时添加badge
            $data['badge'] = 1;
            $ret = XingeAppService::push_ios_by_devicetoken($data);
        }
        //(2)推送跳转消息
        $data = [];
        $custom = [];
        $data['title'] = '美院帮消息';
        $data['sendtime'] = '';
        $data['custom']['t'] = PushMessageTypeEnum::NOTIFY;
        $data['dev_token'] = $tokenmodel['xg_device_token'];
        $fromname = UserDetailService::getNameByUid($params['from_uid']);
        switch ($params['action_type']) {
            case SysMsgTypeEnum::CORRECT_SUBMIT :
                //提交批改
                $data['content'] = $fromname . ' 求你批改作品';
                break;
            case SysMsgTypeEnum::CORRECT_FINISH :
                //完成批改
                $data['content'] = $fromname . ' 批改了你的作品';
                break;
            case SysMsgTypeEnum::CORRECT_REFUSE :
                //拒批
                $data['content'] = $fromname . ' 拒绝批改了你的作品';
                break;
        }
        //只有私信需要修改j参数，其他都是系统消息
        $data['custom']['p']['j'] = 'meiyuan://correctmsg?correctid=' . urlencode($params['correctid']) . '&f_catalog_id=' . $correctinfo['f_catalog_id'];
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($data);
        } else {
            //ios顶部通知增加声音
            $data['sound'] = "default";
            $ret = XingeAppService::push_ios_by_devicetoken($data);
        }
        //(3)消息推送后处理缓存，用于在客户端接收不到推送的情况下，主动获取未读消息时使用
        $redis = Yii::$app->cache;
        if ($params['action_type'] == SysMsgTypeEnum::CORRECT_SUBMIT) {
            $rediskey = "ms:correct" . $params['to_uid'];
        } else {
            $rediskey = "ms:correct_" . $correctinfo['f_catalog_id'] . '_' . $params['to_uid'];
        }
        //记录用户未读的批该信息id
        $redis->lpush($rediskey, $params['correctid']);
    }

    /**
     * 处理课程卷推送
     * 目前单条课程卷也按照批量发送，以后根据使用量在升级
     * @param unknown $params
     */
    private function couponHandle($params) {
        //(1)得到用户数组
        $struids = $params['to_uid'];
        if (!$struids) {
            return;
        }
        $arruid = explode(',', $struids);
        //(2)构建消息体 参数
        $paramsMsg = [];
        $paramsMsg['title'] = '美院帮消息';
        $paramsMsg['sendTime'] = null;
        $paramsMsg['custom']['t'] = PushMessageTypeEnum::NOTIFY;
        $paramsMsg['content'] = '您有' . $params['coupon_name'] . '到帐，请查收';
        $paramsMsg['custom']['p']['j'] = 'meiyuan://couponmsg';
        //ios时顶部声音，andriod时没有作用
        $paramsMsg['sound'] = "default";
        //(3)如果是单条推送则进行单条推送并退出
        if (count($arruid) == 1) {
            $this->couponSinglePush($arruid[0], $paramsMsg);
            return;
        }
        //(4)获取所有的andriod设备和ios设备
        $andriodTokens = UserPushService::getByDevicetype($arruid, 1);
        $iosTokens = UserPushService::getByDevicetype($arruid, 2);
        //(5)andriod群发
        $this->couponMultiPush($andriodTokens, $paramsMsg, true);
        //(6)ios群发
        $this->couponMultiPush($iosTokens, $paramsMsg, false);
    }

    /**
     * 处理课程卷推送
     * 目前单条课程卷也按照批量发送，以后根据使用量在升级
     * @param unknown $params
     */
    private function GroupBuyHandle($params) {
        //(1)得到用户数组
        $struids = $params['to_uid'];
        if (!$struids) {
            return;
        }
        $arruid = explode(',', $struids);
        //(2)构建消息体 参数
        $paramsMsg = [];
        $paramsMsg['title'] = '美院帮消息';
        $paramsMsg['sendTime'] = null;
        $paramsMsg['custom']['t'] = PushMessageTypeEnum::NOTIFY;
        $paramsMsg['content'] = '童鞋，你已成功团购“' . $params['groupbuy_title'] . '”，赶紧去看看吧。';
        $paramsMsg['custom']['p']['j'] = 'meiyuan://groupbuymsg';
        //ios时顶部声音，andriod时没有作用
        $paramsMsg['sound'] = "default";
        //(4)获取所有的andriod设备和ios设备
        $andriodTokens = UserPushService::getByDevicetype($arruid, 1);
        $iosTokens = UserPushService::getByDevicetype($arruid, 2);
        //(5)andriod群发
        $this->groupbuyMultiPush($andriodTokens, $paramsMsg, true);
        //(6)ios群发
        $this->groupbuyMultiPush($iosTokens, $paramsMsg, false);
    }

    /**
     * 团购群发消息
     * @param unknown $pushUsers
     * @param unknown $isAndriod
     */
    private function groupbuyMultiPush($pushUsers, $params, $isAndriod) {
        $redis = Yii::$app->cache;
        //取990个用户进行一次群发
        $part_data = array_splice($pushUsers, 0, 999);
        $tokens = [];
        foreach ($part_data as $k => $v) {
            $tokens[] = $v['xg_device_token'];
            //用户课程卷缓存数量加1
            $rediskey = "ms:groupbuymsg";
            $redis->zincrby($rediskey, 1, $v['uid']);
        }
        //新建批量推送消息
        if ($isAndriod) {
            $ret = XingeAppService::CreateMultipush_andriod($params);
        } else {
            $ret = XingeAppService::CreateMultipush_ios($params);
        }
        if ($ret['ret_code'] == 0) {
            //得到推送id
            $pushId = $ret['result']['push_id'];
        } else {
            $pushId = 0;
            //如果出错则退出群发处理
            return;
        }
        //发送
        $ret = XingeAppService::pushByTokens($pushId, $tokens, $isAndriod);
    }

    /**
     * 群发课程卷消息
     * @param unknown $pushUsers
     * @param unknown $isAndriod
     */
    private function couponMultiPush($pushUsers, $params, $isAndriod) {
        $redis = Yii::$app->cache;

        //信鸽群发最多1000条，所以990发一次不触发临界值
        while (count($pushUsers) > 0) {
            //取990个用户进行一次群发
            $part_data = array_splice($pushUsers, 0, 990);
            $tokens = [];
            foreach ($part_data as $k => $v) {
                $tokens[] = $v['xg_device_token'];
                //用户课程卷缓存数量加1
                $rediskey = "ms:couponmsg";
                $redis->zincrby($rediskey, 1, $v['uid']);
            }
            //新建批量推送消息
            if ($isAndriod) {
                $ret = XingeAppService::CreateMultipush_andriod($params);
            } else {
                $ret = XingeAppService::CreateMultipush_ios($params);
            }
            if ($ret['ret_code'] == 0) {
                //得到推送id
                $pushId = $ret['result']['push_id'];
            } else {
                $pushId = 0;
                //如果出错则退出群发处理
                return;
            }
            //发送
            $ret = XingeAppService::pushByTokens($pushId, $tokens, $isAndriod);
        }
    }

    /**
     * 单个课程卷发放推送
     * @param unknown $uid
     * @param unknown $params
     */
    private function couponSinglePush($uid, $params) {
        $redis = Yii::$app->cache;

        //(1)获取最近使用的设备token
        $tokenmodel = UserPushService::getByUid($uid);
        $params['dev_token'] = $tokenmodel['xg_device_token'];
        //(2)推送跳转消息
        if ($tokenmodel['device_type'] == 1) {
            $ret = XingeAppService::push_android_by_devicetoken($params);
        } else {
            $ret = XingeAppService::push_ios_by_devicetoken($params);
        }     //用户课程卷缓存数量加1
        $rediskey = "ms:couponmsg";
        $redis->zincrby($rediskey, 1, strval($uid));
    }

    /**
     * 清除小红点的处理逻辑
     * 用户看过私信或者系统消息以后，清除缓存
     * 6:系统消息 8:私信,其他数字在原系统中有定义但并未使用
     * @param unknown $params
     */
    private function clearRedHandle($params) {
        $redis = Yii::$app->cache;

        switch ($params['mType']) {
            case 6 :
                //系统消息
                $rediskey = "ms:msg";
                $redis->zrem($rediskey, $params['uid']);
                break;
            case 8 :
                //私信
                $rediskey = "ms:pmsg" . $params['uid'];
                $redis->zrem($rediskey, $params['from_uid']);
                break;
            case 9:
                //批改在获取api中已经直接清除
                break;
        }
    }

}
