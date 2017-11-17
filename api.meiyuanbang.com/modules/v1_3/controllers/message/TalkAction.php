<?php

namespace api\modules\v1_3\controllers\message;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MessageService;
use api\service\UserDetailService;
use common\models\myb\Message;
use common\service\CommonFuncService;
use api\service\CourseService;
use api\service\OrderinfoService;
use api\service\CorrectService;

/**
 * 两人私信对话列表
 * 与老版本相比
 * 去掉last_read_mid
 * 去掉type参数，改为根据last_mid判断是否分页
 * 图片时，resource去掉了rid和description，改为把图片信息存content
 * 添加了声音类型
 */
class TalkAction extends ApiBaseAction {

    public function run() {
        //页数
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        //私信对话人
        $otheruid = $this->requestParam('msg_uid', true);
        //检查是否分页
        $lastid = $this->requestParam('lastmid');
        $com_version = $this->requestParam('com_version');
        if (!$lastid) {
            $lastid = 0;
        }
        $ret['content'] = [];
        //得到对话列表
        $talklist = MessageService::getTalkList($this->_uid, $otheruid, $lastid, $rn);
        //数组中添加用户信息
        if ($talklist) {
            $usermodel = UserDetailService::getByUid($this->_uid);
            $otherusermodel = UserDetailService::getByUid($otheruid);
            foreach ($talklist as $k => $v) {
                //添加用户信息
                if ($usermodel['uid'] == $v['from_uid']) {
                    $curuser = $usermodel;
                } else {
                    $curuser = $otherusermodel;
                }
                $v['sname'] = $curuser['sname'];
                $v['avatar'] = $curuser['avatar'];
                $v['ukind'] = $curuser['ukind'];
                $v['ukind_verify'] = $curuser['ukind_verify'];
                //处理图片
                if ($v['mtype'] == 1) {
                    $resource = json_decode($v['content'], true);
                    $resource['t'] = CommonFuncService::getPicByType($resource['n'], 't');
                    $v['resource'] = $resource;
                    $v['content'] = '';
                } else if ($v['mtype'] == 2) {
                    //处理声音
                    $v['voice'] = json_decode($v['content'], true);
                    $v['content'] = '';
                } else if ($v['mtype'] == 3) {
                    //处理课程
                    if ($com_version < 311) {
                        $v['mtype'] = 0;
                        $v['content'] = "该消息为视频课程，请先升级美院帮APP ，再查看。";
                    } else {
                        $v['course'] = CourseService::getDetail($v['content'], $otheruid);
                        $v['content'] = '';
                    }
                }
                $ret['content'][] = $v;
            }
        }
        //第一次进入私信对话页面时清除小红点
        if ($lastid == 0) {
            MessageService::removeRed($this->_uid, $otheruid);
        }
        
        //增加消费记录课程总价格，改画次数
        if ($otheruid) {
            //查找批改次数
            $correct_number = CorrectService::getTeacherSetUserCorrect($otheruid, $this->_uid);

            $paySuccess = OrderinfoService::getUserOrderSuccess($otheruid);
            if ($paySuccess) {
                $ret['correct_info'] = '共消费' . $paySuccess['info'] . '次（共￥' . $paySuccess['money'] . '元）,你为他改画' . $correct_number . '次';
            } else {
                $ret['correct_info'] = '共消费0次（共￥0元）,你为他改画' . $correct_number . '次';
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
