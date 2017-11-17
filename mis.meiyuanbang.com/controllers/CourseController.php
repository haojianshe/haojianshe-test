<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

class CourseController extends MBaseController {

    //去掉csrf验证，不然post请求会被过滤掉
    public $enableCsrfValidation = false;

    /**
     * mis下所有方法的过滤器
     */
    public function behaviors() {
        return [
            //检查用户登录
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    // 允许认证用户
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'permission' => [
                'class' => 'mis\components\filters\PermissionFilter',
            ],
        ];
    }

    public function actions() {
        return [
            //列表
            'index' => [
                'class' => 'mis\controllers\course\IndexAction',
            ],
            //编辑
            'edit' => [
                'class' => 'mis\controllers\course\EditAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\course\DelAction',
            ],
            'thumbupload' => [
                'class' => 'mis\controllers\course\ThumbUploadAction',
            ],
            //选择批改老师
            'teachersel' => [
                'class' => 'mis\controllers\course\TeacherSelAction',
            ],
            //章节列表
            'section' => [
                'class' => 'mis\controllers\course\SectionAction',
            ],
            //章节编辑
            'section_edit' => [
                'class' => 'mis\controllers\course\SectionEditAction',
            ],
            //删除
            'section_del' => [
                'class' => 'mis\controllers\course\SectionDelAction',
            ],
            //章节视频列表
            'section_video' => [
                'class' => 'mis\controllers\course\SectionVideoAction',
            ],
            //章节视频编辑
            'section_video_edit' => [
                'class' => 'mis\controllers\course\SectionVideoEidtAction',
            ],
            //删除
            'section_video_del' => [
                'class' => 'mis\controllers\course\SectionVideoDelAction',
            ],
            //选择课程
            'coursesel' => [
                'class' => 'mis\controllers\course\CourseSelAction',
            ],
            //选择视频
            'videosel' => [
                'class' => 'mis\controllers\course\VideoSelAction',
            ],
            //推荐分类
            'rec_catalog' => [
                'class' => 'mis\controllers\course\RecCatalogIndexAction',
            ],
            //编辑推荐分类
            'rec_catalog_edit' => [
                'class' => 'mis\controllers\course\RecCatalogEditAction',
            ],
            //推荐分类删除
            'rec_catalog_del' => [
                'class' => 'mis\controllers\course\RecCatalogDelAction',
            ],
            //编辑推荐课程
            'rec_course_edit' => [
                'class' => 'mis\controllers\course\RecCourseEditAction',
            ],
            //推荐分类课程
            'rec_course' => [
                'class' => 'mis\controllers\course\RecCourseIndexAction',
            ],
            //删除推荐课程
            'rec_course_del' => [
                'class' => 'mis\controllers\course\RecCourseDelAction',
            ],
            //二级分类选择
            'select_menu' => [
                'class' => 'mis\controllers\course\SelectMenuAction',
            ],
            //视频专题
            'subject' => [
                'class' => 'mis\controllers\course\SubjectAction',
            ],
            //审核一招
            'operation' => [
                'class' => 'mis\controllers\course\OperationAction',
            ],
            //修改一招
            'update' => [
                'class' => 'mis\controllers\course\UpdateAction',
            ],
            //一招列表
            'curriculum' => [
                'class' => 'mis\controllers\course\CurriculumAction',
            ],
            //一招课程列表排序
            'editem' => [
                'class' => 'mis\controllers\course\EditemAction',
            ],
            //一招添加课程
            'recommend' => [
                'class' => 'mis\controllers\course\RecommendAction',
            ],
            //选课
            'delvideosubject' => [
                'class' => 'mis\controllers\course\DelVideoSubjectAction',
            ],
            //IOS价格选择
            'ios_price_sel' => [
                'class' => 'mis\controllers\course\IosPriceSelAction',
            ],
            //选择整课课程
            'course_list' => [
                'class' => 'mis\controllers\course\CourseListAction',
            ],
        ];
    }

}
