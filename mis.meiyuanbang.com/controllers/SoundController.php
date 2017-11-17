<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 语音管理
 */
class SoundController extends MBaseController {

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
                'only' => ['index', 'edit', 'soundupload'],
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
                'only' => ['index', 'edit', 'soundupload'],
            ],
        ];
    }


    /**
     * 工具
     */
    public function actions() {
        return [
            //语音列表
            'index' => [
                'class' => 'mis\controllers\sound\IndexAction',
            ],
            //语音选择
            'sel' => [
                'class' => 'mis\controllers\sound\SelAction',
            ],
            //编辑
            'edit' => [
                'class' => 'mis\controllers\sound\EditAction',
            ],
         	//上传视频
        	'soundupload' => [
     			'class' => 'mis\controllers\sound\SoundUploadAction',
         	],
            'thumbupload' => [
                'class' => 'mis\controllers\course\ThumbUploadAction',
            ],
        ];
    }
}
