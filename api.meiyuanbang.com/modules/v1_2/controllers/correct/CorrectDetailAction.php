<?php

namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\ResourceService;
use api\service\CorrectTalkService;
use api\service\UserDetailService;
use api\service\UserRelationService;
use api\service\UserCorrectService;
use api\service\FavoriteService;
use common\service\CommonFuncService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\TweetService;
use api\service\OrderinfoService;

/**
 * 批改详情
 */
class CorrectDetailAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
        $submituid = $this->_uid;
        $correctid = $this->requestParam('correctid', true);

        $correct_info = CorrectService::getCorrectDetail($correctid);
        //已批改的个数
        $correct_info['teacher_correct_pic_num'] = CorrectService::getCorrectSuccess($correct_info['source_pic_rid']);

        //清除小红点       
        CorrectService::clearRedCorrectNum($this->_uid, $correctid, $correct_info['f_catalog_id']);

        if ($correct_info["status"] == 2) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        }
        //主评论语音
        if (!empty($correct_info['majorcmt_id'])) {
            $correct_info['majorcmt'] = CorrectTalkService::getCorrectTalkDetail($correct_info['majorcmt_id']);
        } else {
            $correct_info['majorcmt'] = (object) null;
        }

        if (!empty($correct_info['source_pic_rid'])) {
            //原图
            $correct_info['source_pic'] = ResourceService::getResourceDetail($correct_info['source_pic_rid']);
            $correct_info['source_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 's');
        } else {
            $correct_info['source_pic'] = (object) null;
        }

        if (!empty($correct_info['correct_pic_rid'])) {
            //批改后图片
            $correct_info['correct_pic'] = ResourceService::getResourceDetail($correct_info['correct_pic_rid']);
            $correct_info['correct_pic']['img']->s = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 's');
        } else {
            $correct_info['correct_pic'] = (object) null;
        }
        //如果批改后没有示例图片 则随机取素材图片
        /* if($correct_info['status']==1 && empty($correct_info['example_pics'])){
          $correct_info['example_pics']=TweetService::getCorrectExampleImgRand();
          } */
        //示例图片
        $example_pics_arr = explode(',', $correct_info['example_pics']);
        if ($correct_info['example_pics']) {
            foreach ($example_pics_arr as $key1 => $value1) {
                $example_pics[] = ResourceService::getResourceDetail($value1);
                $example_pics[$key1]['img']->t = CommonFuncService::getPicByType((array) $example_pics[$key1]['img']->n, 't');
            }
        } else {
            $example_pics = array();
        }
        $correct_info['example_pic'] = $example_pics;
        $example_pics = array();

        //图片上的语音
        $pointcmt_ids_arr = explode(',', $correct_info['pointcmt_ids']);
        if ($correct_info['pointcmt_ids']) {
            foreach ($pointcmt_ids_arr as $key1 => $value1) {
                $pointcmts_arr[] = CorrectTalkService::getCorrectTalkDetail($value1);
            }
        } else {
            $pointcmts_arr = array();
        }

        $correct_info['submit_info'] = UserDetailService::getByUid($correct_info['submituid']);
        $teacher_info = UserDetailService::getByUid($correct_info['teacheruid']);
        $teacher_correct_info = UserCorrectService::getUserCorrectDetail($correct_info['teacheruid']);
        $correct_info['teacher_info'] = array_merge($teacher_info, $teacher_correct_info);


        //判断当前用户是老师还是学生 分享不同的网址

        $user_info = UserCorrectService::getUserCorrectDetail($this->_uid);

        if (!empty($user_info)) {
            $correct_info['share_url'] = Yii::$app->params['sharehost'] . '/correct/share?correctid=' . $correct_info['correctid'] . '&user_type=teacher';
        } else {
            $correct_info['share_url'] = Yii::$app->params['sharehost'] . '/correct/share?correctid=' . $correct_info['correctid'] . '&user_type=submit';
        }
        //获取用户批改老师关注类型
        $correct_info['follow_type'] = UserRelationService::getBy2Uid($this->_uid, $correct_info['teacheruid']);
        if ($correct_info['status'] == 1) {
            $correct_info['title'] = $correct_info['teacher_info']['sname'] . '老师批改的' . $correct_info['f_catalog'] . '作品';
        } else {
            $correct_info['title'] = $correct_info['submit_info']['sname'] . '同学的' . $correct_info['f_catalog'] . '作品';
        }
        $correct_info['correct_title'] = '已批改了' . $correct_info['submit_info']['sname'] . '的画作';
        $correct_info['pointcmt'] = $pointcmts_arr;
        if ($correct_info['correct_time']) {
            $correct_info['correct_time_format'] = CommonFuncService::format_time($correct_info['correct_time']);
        } else {
            $correct_info['correct_time_format'] = '';
        }

        $correct_info['ctime_format'] = CommonFuncService::format_time($correct_info['ctime']);
        //增加帖子信息       
        $tweet_info = TweetService::fillExtInfo($correct_info['tid'], $this->_uid, true);
        $correct_info['praise'] = $tweet_info['praise'];
        $correct_info['follow_type'] = $tweet_info['follow_type'];
        $correct_info['lessonid'] = $tweet_info['lessonid'];
        $correct_info['share']['title'] = '吊炸天的全方位在线评画功能';
        $correct_info['share']['desc'] = '全面又细致，你也来试试';
        $correct_info['share']['url'] = $correct_info['share_url'];
        if ($correct_info['correct_pic_rid']) {
            $correct_info['share']['img'] = CommonFuncService::getPicByType((array) $correct_info['correct_pic']['img']->n, 't')['url'];
        } else {
            $correct_info['share']['img'] = CommonFuncService::getPicByType((array) $correct_info['source_pic']['img']->n, 't')['url'];
        }
        unset($tweet_info['correct']);
        $correct_info['tweet_info'] = $tweet_info;
        $correct_info['fav'] = FavoriteService::getFavStatusByUidTid($this->_uid, $correct_info['tid']);
        $favinfo=FavoriteService::getFavInfoByContent($correct_info['tid'],0);
        $correct_info=array_merge($correct_info,$favinfo);

        //查找批改次数
         $correct_number= CorrectService::getTeacherSetUserCorrect($correct_info['submituid'],$this->_uid);
         
        
        $pointcmts_arr = array();
        $data = $correct_info;
        //帖子访问量+1
        TweetService::addHits($correct_info['tid']);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
