<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 活动管理
 */
class PrizegameController extends MBaseController {

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
            //显示活动列表
            'index' => [
                'class' => 'mis\controllers\prizegame\IndexAction',
            ],
            //添加或者修改用户
            'edit' => [
                'class' => 'mis\controllers\prizegame\EditAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\prizegame\ThumbUploadAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\prizegame\DelAction',
            ],
        ];
    }

}
