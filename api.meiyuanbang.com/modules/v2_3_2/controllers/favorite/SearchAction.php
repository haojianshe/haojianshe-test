<?php

namespace api\modules\v2_3_2\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\TweetService;
use api\service\MaterialSubjectService;
use api\service\NewsService;
use common\service\CommonFuncService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;
use api\service\LectureService;
use api\service\LessonService;
use api\service\CourseService;

/**
 * 收藏分类搜索
 */
class SearchAction extends ApiBaseAction {

    public function run() {
        $type = $this->requestParam('type', true);
        $content = $this->requestParam('content');
        if ($this->_uid == -1) {
            $uid = $this->requestParam('uid');
        } else {
            $uid = $this->_uid;
        }
        $last_fid = $this->requestParam('last_fid') ? $this->requestParam('last_fid') : NULL;
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 20;
        $return_data = [];

        switch ($type) {
            //素材 批改
            case '0':case '3':
                $ids = FavoriteService::searchFavTweet($uid, $content, $type, $last_fid, $rn);
                if ($ids) {

                    foreach ($ids as $key => $tid) {
                        $tweet = TweetService::fillExtInfo($tid['tid'], $this->_uid, true);
                        if (false === $tweet || empty($tweet)) {
                            continue;
                        }
                        //tudo type<3 取平论  批改不带评论
                        if ($tweet['type'] < 3) {
                            $tweet['comment_list'] = TweetService::getCmtRedis(0, $tweet['tid'], 2);
                        } else {
                            $tweet['comment_list'] = array();
                        }
                        //判断加精 推荐等状态
                        $tweet = TweetService::fillFlag($tweet);
                        //添加图片列表
                        $tweet['imgs_list'] = $tweet['imgs'];
                        //多图时显示第一图
                        if ($tweet['picnum'] > 0) {
                            $tweet['imgs'] = $tweet['imgs'][0];
                        }
                        //跳转跟着画  0为空 不显示
                        if (empty($tweet['lessonid'])) {
                            $tweet['lessonid'] = 0;
                        }
                        //$tweet['fid'] = $model['fid'];
                        $data_t = $tid;
                        $data_t['tweet_info'] = $tweet;
                        $data_t['material_info'] = (object) array();
                        $data_t['lecture_info'] = (object) array();
                        $return_data[] = $data_t;
                    }
                }
                break;
            case '1':
                //专题
                $mids = FavoriteService::searchFavMaterial($uid, $content, $last_fid, $rn);
                if ($mids) {
                    foreach ($mids as $key => $mid) {

                        $tmp = MaterialSubjectService::getMaterialDetail($mid['tid']);
                        if ($tmp) {
                            $data_t = $mid;
                            $tmp["picurl"] = json_decode($tmp["picurl"]);
                            $tmp["picurl"]->l = CommonFuncService::getPicByType((array) $tmp["picurl"]->n, "l");
                            $data_t['tweet_info'] = (object) array();
                            $data_t['material_info'] = $tmp;
                            $data_t['lecture_info'] = (object) array();
                            $return_data[] = $data_t;
                        }
                    }
                }
                break;
            case '2':
                //文章
                $newsids = FavoriteService::searchFavLecture($uid, $content, $last_fid, $rn);
                if ($newsids) {
                    foreach ($newsids as $key => $newsid) {
                        //精讲
                        $tmp = NewsService::getLectureInfo($newsid['tid']);
                        if ($tmp) {
                            $data_t = $newsid;
                            $data_t['tweet_info'] = (object) array();
                            $data_t['material_info'] = (object) array();
                            $data_t['lecture_info'] = $tmp;
                            $return_data[] = $data_t;
                        }
                    }
                }
                break;
            // 0/1/2/3/4/   5/6/7/8/9 帖子/专题/精讲文章/活动文章/活动问答/能力模型素材/收藏直播课/收藏课程/精讲专题/跟着画
            case '6':
                //直播
                $livdids = FavoriteService::searchFavLive($uid, $content, $last_fid, $rn);
                if ($livdids) {
                    foreach ($livdids as $key => $liveid) {
                        //直播
                        $data_t = $liveid;
                        #$data_t['type'] = 6;
                        $data_t['live_info'] = LiveService::getDetail($liveid['tid']); #getDetail
                        $return_data[] = $data_t;
                    }
                }
                break;
            case '7':
                //课程
                $livdids = FavoriteService::searchFavCourse($uid, $content, $last_fid, $rn);
                if ($livdids) {
                    foreach ($livdids as $key => $liveid) {
                        //课程
                        $data_t = $liveid;
                        $data_t['course_info'] = CourseService::getDetail($liveid['tid'],$uid);
                        $return_data[] = $data_t;
                    }
                }
                break;
            case '8':
                //精讲专题
                $livdids = FavoriteService::searchFavSubject($uid, $content, $last_fid, $rn);
                if ($livdids) {
                    foreach ($livdids as $key => $liveid) {
                        //课程
                        #$data_t['type'] = 8;
                        $data_t = $liveid;
                        $data_t['lecture_subject_info'] = LectureService::getLectureInfo($liveid['tid']);
                        $return_data[] = $data_t;
                    }
                }
                break;
            case '9':
                //跟着画
                $livdids = FavoriteService::searchFavLesson($uid, $content, $last_fid, $rn);
                if ($livdids) {
                    foreach ($livdids as $key => $liveid) {
                        //课程
                        # $data_t['type'] = 9;
                        $data_t = $liveid;
                        $data_t['lesson_info'] = LessonService::getLessonOne($liveid['tid']);
                        $return_data[] = $data_t;
                    }
                }
                break;
        }
        $data['content'] = $return_data;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
