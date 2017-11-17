<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 出版社管理
 */
class PublishController extends MBaseController {

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
            //显示出版社列表
            'index' => [
                'class' => 'mis\controllers\publish\IndexAction',
            ],
            //添加或者修改用户
            'edit' => [
                'class' => 'mis\controllers\publish\EditAction',
            ],
            //录入用户信息
            'set_user' => [
                'class' => 'mis\controllers\publish\SetUserAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\publish\ThumbuploadAction',
            ],
            //缩略图上传
            'thumbuploadbook' => [
                'class' => 'mis\controllers\publish\ThumbUploadBookAction',
            ],
            //广告位管理
            'advert' => [
                'class' => 'mis\controllers\publish\AdvertAction',
            ],
            //广告位s删除
            'deladvert' => [
                'class' => 'mis\controllers\publish\DeladvertAction',
            ],
            //图书上传
            'delbook' => [
                'class' => 'mis\controllers\publish\DelbookAction',
            ],
            //广告位编辑删除
            'editadvert' => [
                'class' => 'mis\controllers\publish\EditadvertAction',
            ],
            //图书管理
            'bookmanage' => [
                'class' => 'mis\controllers\publish\BookmanageAction',
            ],
            //图书管理 添加/编辑
            'editbook' => [
                'class' => 'mis\controllers\publish\EditbookAction',
            ],
            //图书管理 添加/编辑
            'select_menu' => [
                'class' => 'mis\controllers\publish\SelectMenuAction',
            ],
            //传图片
            'picupload' => [
                'class' => 'mis\controllers\publish\PicUploadAction',
            ],
            //出版社推荐管理
            'recommended' => [
                'class' => 'mis\controllers\publish\RecommendedAction',
            ],
             //出版社推荐字段排序/删除
            'delbookadv' => [
                'class' => 'mis\controllers\publish\DelbookadcAction',
            ],
             //出版社添加推荐列表
            'addbookadv' => [
                'class' => 'mis\controllers\publish\AddbookadvAction',
            ],
             //出版社添加推荐
            'addadvbook' => [
                'class' => 'mis\controllers\publish\AddadvbookAction',
            ],
            
        ];
    }

}
