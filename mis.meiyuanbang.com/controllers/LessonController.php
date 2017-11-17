<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 考点管理功能，
 */
class LessonController extends MBaseController {

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
            //显示考点列表
            'index' => [
                'class' => 'mis\controllers\lesson\IndexAction',
            ],
            //添加或者修改考点基本信息
            'edit' => [
                'class' => 'mis\controllers\lesson\EditAction',
            ],
            //ajax获取考点分类型
            'ajaxsubtype' => [
                'class' => 'mis\controllers\lesson\AjaxSubTypeAction',
            ],
            //发布考点
            'publish' => [
                'class' => 'mis\controllers\lesson\PublishAction',
            ],
            //考点取消发布
            'cancelpublish' => [
                'class' => 'mis\controllers\lesson\CancelPublishAction',
            ],
            //删除考点
            'del' => [
                'class' => 'mis\controllers\lesson\DelAction',
            ],
            //ajax获取考点分类型
            'dashboard' => [
                'class' => 'mis\controllers\lesson\DashboardAction',
            ],
            //图片上传
            'picupload' => [
                'class' => 'mis\controllers\lesson\PicUploadAction',
            ],
            //添加或删除节点
            'sectionedit' => [
                'class' => 'mis\controllers\lesson\SectionEditAction',
            ],
            //编辑节点图片
            'sectionimg' => [
                'class' => 'mis\controllers\lesson\SectionImgAction',
            ],
            //删除图片
            'sectionimg' => [
                'class' => 'mis\controllers\lesson\SectionImgAction',
            ],
            //删除图片
            'imgdel' => [
                'class' => 'mis\controllers\lesson\ImgDelAction',
            ],
            //设置图片listorder
            'setimglistorder' => [
                'class' => 'mis\controllers\lesson\SetImgListorderAction',
            ],
            //筛选
            'select_menu' => [
                'class' => 'mis\controllers\lesson\SelectMenuAction',
            ],
             //图片上传
            'thumbupload' => [
                'class' => 'mis\controllers\lesson\ThumbUploadAction',
            ], 
            //考点描述列表
            'desc' => [
                'class' => 'mis\controllers\lesson\DescAction',
            ],
            //考点描述列表
            'descedit' => [
                'class' => 'mis\controllers\lesson\DescEditAction',
            ],
            //考点描述删除
            'descdel' => [
                'class' => 'mis\controllers\lesson\DescDelAction',
            ],
            //描述图片撒谎给你传
            'descthumbupload' => [
                'class' => 'mis\controllers\lesson\DescThumbUploadAction',
            ],
        ];
    }

}
