<?php

namespace api\modules\v1_3\controllers\cmt;

use Yii;
use api\components\ApiBaseAction;
use api\service\CommentService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use api\service\ResourceService;
use api\service\UserCoinService;
use api\service\CointaskService;
use common\service\dict\CointaskDictService;
use api\service\ActivityQaService;
use api\service\CapacityModelMaterialService;
use api\service\MaterialSubjectService;
use mobile\service\LiveBlackService;

/**
 * 获取加入小组用户
 */
class NewCmtAction extends ApiBaseAction {

    public function run() {
        $content = array();
        //检查评论类型和主体id必须传入
        $subjecttype = $this->requestParam('subjecttype', true);
        $subjectid = $this->requestParam('subjectid', true);
        $ctype = $this->requestParam('ctype');
        $reply_uid = $this->requestParam('reply_uid');
        $reply_cid = $this->requestParam('reply_cid');
        $ctype = isset($ctype) ? $ctype : 0;
        $reply_uid = isset($reply_uid) ? $reply_uid : 0;
        $reply_cid = isset($reply_cid) ? $reply_cid : 0;
        $uid = $this->_uid;
        if ($uid == -1) {
            die("用户未登录！！");
        }
        if ($ctype == 1) {
            //图片
            $file = $_FILES['file'];
            $resource = ResourceService::uploadPicFile('cmt', $file);
            if (!empty($resource['message'])) {
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $resource);
            } else {
                $content = json_encode($resource['img']);
            }
        } elseif ($ctype == 0) {
            $content = trim($this->requestParam('content', true));
        } elseif ($ctype == 2) {
            //语音
            $file = $_FILES['file'];
            //时长duration
            $duration = round($this->requestParam('duration', true));
            $talk = ResourceService::uploadTalkFile('cmt', $file, $duration);
            if (!empty($talk['message'])) {
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $talk);
            } else {
                $content = json_encode($talk);
            }
        }elseif ($ctype == 4) {
            $content = trim($this->requestParam('content', true));
        } 
        //检查问答提问规则
        if (intval($subjecttype) == 8 && intval($reply_uid == 0)) {
            $qa_info = ActivityQaService::getQaDetail($subjectid);
            if ((intval($qa_info['cmtcount']) >= intval($qa_info['ask_limit'])) && (intval($qa_info['ask_limit']) > 0)) {
                $data['message'] = '童靴，提问已达到上限，不能提问了。';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
            }
        }

        //直播评论
        if (intval($subjecttype) == 10) {
            //查询该用户是否是黑名单用户
            $result = LiveBlackService::getBlackUserList($uid, $subjectid);
            //用户存在黑名单记录
            if (!empty($result)) {
                if ($result['no_talking_time'] > 1000000000) {
                    $data['data'] = 2;
                    $data['ctime'] = 0;
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
                if ($result['no_talking_time'] == 80 && time() - $result['ctime'] < 80) {
                    $data['data'] = 3;
                    $data['ctime'] = time() - $result['ctime'];
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
            }
            if ($content == 1000000000) {
                $content = 100000000;
            }
        }

        $model = new CommentService();
        $model->uid = $uid;
        $model->subjecttype = $subjecttype;
        $model->subjectid = $subjectid;
        $model->ctype = $ctype;
        $model->content = $content;
        $model->ctime = time();
        $model->reply_uid = $reply_uid;
        $model->reply_cid = $reply_cid;
        $model->save();

        $cid = $model->attributes['cid'];
        //清除评论数缓存
        if (intval($subjecttype) == 8) {
            //问答评论处理语音成mp3格式
            if (intval($model->ctype) == 2) {
                CommentService::AddVoiceToMp3Task($cid);
            }
            //活动问答单独处理
            if (intval($reply_uid == 0)) {
                CommentService::incCmtCountRedis($subjecttype, $subjectid);
            }
        } else {
            CommentService::incCmtCountRedis($subjecttype, $subjectid);
        }
        //能力模型素材更改更新时间
        if (intval($subjecttype) == 9) {
            $material = CapacityModelMaterialService::findOne(['materialid' => $subjectid]);
            $material->utime = time();
            $ret = $material->save();
            if ($ret) {
                //检查是否需要添加评论金币
                $tasktype = CointaskTypeEnum::COMMENT;
                if (CointaskService::IsAddByDaily($uid, $tasktype)) {
                    //需要加金币
                    $coinCount = CointaskDictService::getCoinCount($tasktype);
                    UserCoinService::addCoinNew($uid, $coinCount);
                    $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
                }
            }
        }
        //处理直播写入
        if (intval($subjecttype == 10)) {
            $cid = $model->attributes['cid'];
            $utime = $model->attributes['ctime'] * -1;
            $redis = Yii::$app->cache;
            $redis_key = 'comment_list_' . $subjectid;
            $min = time() * -1;
            $redis->zadd($redis_key, $utime, $cid);
            $res = $redis->zrangebyscore($redis_key, $min, '+inf', [0, 100]);
            if ($res) {
                $data['data'] = 1;
                $data['ctime'] = 0;
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                $redis->delete($redis_key);
            } else {
                $data['data'] = 0;
                $data['ctime'] = 0;
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
            }
        }
        $addcoincount = 0; //UserCoinService::addCoinsByUid($uid,SysMsgTypeEnum::ADDCOIN_COMMENT_TYPE,SysMsgTypeEnum::DAY_COMMENT_MAX_COUNT,SysMsgTypeEnum::NEW_CMMENT_GET_COINS);
        $data['cid'] = $cid;
        $data['addcoincount'] = $addcoincount;
        if ($ctype == 1 or $ctype == 2) {
            $data['resource'] = json_decode($model->attributes['content']);
        } else {
            $data['resource'] = (object) null;
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
