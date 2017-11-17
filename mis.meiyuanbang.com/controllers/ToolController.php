<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis首页类
 */
class ToolController extends MBaseController {

    //public $layout = 'frameinner';
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
     * 工具
     */
    public function actions() {
        return [
            //显示所有包列表
            'index' => [
                'class' => 'mis\controllers\tool\IndexAction',
            ],
            //批量添加页面
            'materialaddh' => [
                'class' => 'mis\controllers\tool\AddMoreAction',
            ],
            //批量添加能力素材接口
            'materialadd' => [
                'class' => 'mis\controllers\tool\AddApiAction',
            ],
            //批量获取文件
            'list' => [
                'class' => 'mis\controllers\tool\ListAction',
            ],
            //清空CDN缓存
            'removecache' => [
                'class' => 'mis\controllers\tool\RemovecacheAction',
            ],
        ];
    }

}
