<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 活动管理
 */
class PosidController extends MBaseController {

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
            //显示推荐列表
            'index' => [
                'class' => 'mis\controllers\posid\IndexAction',
            ],
            //素材列表推荐列表
            'material' => [
                'class' => 'mis\controllers\posid\MaterialAction',
            ],
            //批改列表推荐列表
            'correct' => [
                'class' => 'mis\controllers\posid\CorrectAction',
            ],
            //添加或者修改推荐
            'edit' => [
                'class' => 'mis\controllers\posid\EditAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\posid\ThumbUploadAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\posid\DelAction',
            ],
            //能力模型图书推荐
            'abilitybook' => [
                'class' => 'mis\controllers\posid\AbilitybookAction',
            ],
            //美院帮图书推荐
            'mybbook' => [
                'class' => 'mis\controllers\posid\MybbookAction',
            ],
            //美院帮图书列表删除
            'delbook' => [
                'class' => 'mis\controllers\posid\DelbookAction',
            ],
            //美院帮添加推荐列表
            'addbookadv' => [
                'class' => 'mis\controllers\posid\AddbookadvAction',
            ],
             //能力模型添加美院帮数据到推荐
            'addmybbook' => [
                'class' => 'mis\controllers\posid\AddmybbookAction',
            ],
            
            //出版社推荐管理
            'recommended' => [
                'class' => 'mis\controllers\posid\RecommendedAction',
            ],
            //美院帮推荐删除
            'addadvbook' => [
                'class' => 'mis\controllers\posid\AddadvbookAction',
            ],
            //能力模型推荐
            'abilitybook' => [
                'class' => 'mis\controllers\posid\AbilitybookAction',
            ],
            //能力模型推荐删除
            'delbookadv' => [
                'class' => 'mis\controllers\posid\DelbookadvAction',
            ],
            //删除能力模型推荐
            'delmybbook' => [
                'class' => 'mis\controllers\posid\DelmybbookAction',
            ],
        ];
        
    }

}
