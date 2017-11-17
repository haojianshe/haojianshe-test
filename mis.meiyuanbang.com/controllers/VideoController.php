<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis首页类
 */
class VideoController extends MBaseController {

    //public $layout = 'frameinner';
        //去掉csrf验证，不然post请求会被过滤掉
    public $enableCsrfValidation = false;
    /**
     * mis下所有方法的过滤器
     */
    public function behaviors()
    {
        return [
            //检查用户登录
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'only' => ['index', 'edit', 'videoupload','thumbupload'],
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
                'only' => ['index', 'edit', 'videoupload','thumbupload'],
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
                'class' => 'mis\controllers\video\IndexAction',
            ],
            //编辑
            'edit' => [
                'class' => 'mis\controllers\video\EditAction',
            ],
            //批量添加页面
            'notice' => [
                'class' => 'mis\controllers\video\NoticeAction',
            ],
         	//上传视频
        	'videoupload' => [
     			'class' => 'mis\controllers\video\UploadAction',
         	],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\video\ThumbUploadAction',
            ],
        ];
    }
}
