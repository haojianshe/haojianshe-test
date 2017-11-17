<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 精讲管理功能
 */
class LectureController extends MBaseController {

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

    /**
     * action集合 
     */
    public function actions() {
        return [
            //显示精讲内容列表
            'index' => [
                'class' => 'mis\controllers\lecture\IndexAction',
            ],
            'addtag' => [
                'class' => 'mis\controllers\lecture\AddtagAction',
            ],
            //添加或者修改精讲
            'edit' => [
                'class' => 'mis\controllers\lecture\EditAction',
            ],
            //ajax获取精讲分类型
            'ajaxsubtype' => [
                'class' => 'mis\controllers\lecture\AjaxSubTypeAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\lecture\ThumbUploadAction',
            ],
            //审核
            'audit' => [
                'class' => 'mis\controllers\lecture\AuditAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\lecture\DelAction',
            ],
            //删除标签
            'del_tag' => [
                'class' => 'mis\controllers\lecture\DelTagAction',
            ],
            //添加活动
            'copynewdata' => [
                'class' => 'mis\controllers\lecture\CopynewdataAction',
            ],
            //判断活动是否已经添加过
            'settitle' => [
                'class' => 'mis\controllers\lecture\SettitleAction',
            ],
            //获取标签列表
            'select_menu' => [
                'class' => 'mis\controllers\lecture\SelectMenuAction',
            ],
            //获取二级分类选中的标签列表
            'select_tag' => [
                'class' => 'mis\controllers\lecture\SelectTagAction',
            ],
            'ztop' => [
                'class' => 'mis\controllers\lecture\ZtopAction',
            ],
            'aedit_tag' => [
                'class' => 'mis\controllers\lecture\AeditTagAction',
            ],
            'sel' => [
                'class' => 'mis\controllers\lecture\SelAction',
            ],
            'addadvbook' => [
                'class' => 'mis\controllers\lecture\AddadvbookAction',
            ],
            'add_tag_news' => [
                'class' => 'mis\controllers\lecture\AddTagNewsAction',
            ],
            'sort' => [
                'class' => 'mis\controllers\lecture\SortAction',
            ],
            'delvideosubject' => [
                'class' => 'mis\controllers\lecture\DelVideoSubjectAction',
            ],
        ];
    }

}
