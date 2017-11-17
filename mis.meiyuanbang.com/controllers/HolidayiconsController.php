<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis节日图标
 */
class HolidayiconsController extends MBaseController {

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
                'only' => ['index', 'edit'],
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
                'only' => ['index', 'edit'],
            ],
        ];
    }


    public function actions() {
        return [
            //显示列表
            'index' => [
                'class' => 'mis\controllers\holidayicons\IndexAction',
            ],
            //编辑
            'edit' => [
                'class' => 'mis\controllers\holidayicons\EditAction',
            ],
            //上传图片
            'thumbupload' => [
                'class' => 'mis\controllers\holidayicons\ThumbUploadAction',
            ],
            //更改状态
            'update' => [
                'class' => 'mis\controllers\holidayicons\UpdateAction',
            ],            
        ];
    }
}
