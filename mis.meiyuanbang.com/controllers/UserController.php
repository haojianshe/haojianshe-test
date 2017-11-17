<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 学员管理页面
 */
class UserController extends MBaseController {

    //去掉csrf验证，不然post请求会被过滤掉
    public $enableCsrfValidation = false;

    /**
     * mis下所有方法的过滤器
     */
    public function behaviors() {
        return [
            //检查学员登录
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
            //学员列表
            'index' => [
                'class' => 'mis\controllers\user\IndexAction',
            ],
            //添加或者修改学员
            'edit' => [
                'class' => 'mis\controllers\user\EditAction',
            ],
            //审核
            'audit' => [
                'class' => 'mis\controllers\user\AuditAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\user\DelAction',
            ],
        ];
    }

}
