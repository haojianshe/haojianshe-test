<?php

namespace api\modules\v2_3_5\controllers;

use api\components\ApiBaseController;

/**
 * 联考相关接口
 */
class LkController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['submit_paper'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            ],
        ];
    }

    public function actions() {
        return [
            //省份列表
            'province_list' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\ProvinceListAction',
            ],
            //联考文章列表
            'article_list' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\ArticleListAction',
            ],
            //上传图片
            'upload_pic' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\UploadPicAction',
            ],
            //H5上传图片
            'upload_pic_h' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\UploadPicHAction',
            ],
            //提交模拟考试卷
            'submit_paper' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\SubmitPaperAction',
            ],
            //获取联考模拟考信息
            'get_info' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\GetInfoAction',
            ],
            //状元分享会列表
            'top_students_qa' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\TopStudentsQaAction',
            ],
            //名师大讲堂
            'teacher_lecture' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\TeacherLectureAction',
            ],
            //联考攻略
            'exam_method' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\ExamMethodAction',
            ],
            //榜单
            'rank' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\RankAction',
            ],
            //查询图片
            'get_pic' => [
                'class' => 'api\modules\v2_3_5\controllers\lk\GetPicAction',
            ],
        ];
    }

}
