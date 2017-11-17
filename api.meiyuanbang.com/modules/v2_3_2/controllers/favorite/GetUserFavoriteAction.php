<?php

namespace api\modules\v2_3_2\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\TweetService;
use api\service\MaterialSubjectService;
use api\service\NewsService;
use api\service\ActivityQaService;
use api\service\ActivityArticleService;
use api\service\CapacityModelMaterialService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;
use api\service\CourseService;
use api\service\LectureService;
use common\service\CommonFuncService;
use api\service\LessonService;

/**
 * 收藏列表
 */
class GetUserFavoriteAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        $lastfid = $this->requestParam('last_fid');
        if (!$lastfid) {
            $lastfid = 0;
        }
       # $this->_uid = 60504;
        //获取列表
        $tmplist = FavoriteService::getAllListByUid($this->_uid, $lastfid, $rn);
        $ret = [];
        if (!$tmplist || count($tmplist) == 0) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $ret]);
        }

        //返回收藏列表   0/1/2/3/4 帖子/专题/普通文章/活动文章/活动问答
        foreach ($tmplist as $model) {
            $data_t = $model;
            $data_t['tweet_info'] = (object) array();
            $data_t['material_info'] = (object) array();
            $data_t['lecture_info'] = (object) array();
            $data_t['activity_article_info'] = (object) array();
            $data_t['activity_qa_info'] = (object) array();
            $data_t['capacity_material_info'] = (object) array();
            $data_t['live_info'] = (object) array();
            $data_t['course_info'] = (object) array();
            $data_t['lecture_subject_info'] = (object) array();
            $data_t['lesson_subject_info'] = (object) array();

            //区分type 取不同信息
            if ($model['type'] == 0) {
                //帖子
                $tid = $model['tid'];
                $tweet = TweetService::fillExtInfo($tid, $this->_uid, true);
                if (false === $tweet || empty($tweet) || $tweet['is_del'] == 1) {
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
                $tweet['fid'] = $model['fid'];
                $data_t['tweet_info'] = $tweet;
                $data_t['material_info'] = (object) array();
                $data_t['lecture_info'] = (object) array();
                $ret[] = $data_t;
            } else if ($model['type'] == 1) {
                //专题
                $tmp = MaterialSubjectService::getMaterialDetail($model['tid']);
                if ($tmp) {
                    $tmp["picurl"] = json_decode($tmp["picurl"]);
                    $tmp["picurl"]->l = CommonFuncService::getPicByType((array) $tmp["picurl"]->n, "l");
                    $data_t['tweet_info'] = (object) array();
                    $data_t['material_info'] = $tmp;
                    $data_t['lecture_info'] = (object) array();
                    $ret[] = $data_t;
                }
            } else if ($model['type'] == 2) {
                //普通文章
                $tmp = LectureService::getLectureInfo($model['tid']);
                if ($tmp) {
                    $data_t['tweet_info'] = (object) array();
                    $data_t['material_info'] = (object) array();
                    $data_t['lecture_info'] = $tmp;
                    $ret[] = $data_t;
                }
            } else if ($model['type'] == 3) {
                //活动文章
                $data_t['activity_article_info'] = ActivityArticleService::getArticleDetail($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 4) {
                //活动问答
                $data_t['activity_qa_info'] = ActivityQaService::getQaDetail($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 5) {
                //活动问答
                $data_t['capacity_material_info'] = CapacityModelMaterialService::getMatreialDetail($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 6) {
                //直播
                $data_t['live_info'] = LiveService::getDetail($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 7) {
                //课程
                $data_t['course_info'] = CourseService::getDetail($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 8) {
                //精讲文章专题
                $data_t['lecture_subject_info'] = LectureService::getLectureInfo($model['tid'], $this->_uid);
                $ret[] = $data_t;
            } else if ($model['type'] == 9) {
                //跟着画
                $data_t['lesson_info'] = LessonService::getLessonWithFirstPic($model['tid'], $this->_uid);
                $ret[] = $data_t;
            }
        }
        $data['content'] = $ret;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
