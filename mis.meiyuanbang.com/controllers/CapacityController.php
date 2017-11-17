<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 能力模型
 */
class CapacityController extends MBaseController {

    //public $layout = 'frameinner';
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
     * 用户相关的action集合 
     */
    public function actions() {
        return [
            //能力素材列表
            'material' => [
                'class' => 'mis\controllers\capacity\IndexAction',
            ],
            //批量添加页面
            'materialaddh' => [
                'class' => 'mis\controllers\capacity\AddMoreAction',
            ],
            //批量添加能力素材接口
            'materialadd' => [
                'class' => 'mis\controllers\capacity\AddApiAction',
            ],
            //编辑单个页面
            'materialedit' => [
                'class' => 'mis\controllers\capacity\EditAction',
            ],
            //删除单个 
            'materialdel' => [
                'class' => 'mis\controllers\capacity\DelAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\capacity\ThumbUploadAction',
            ],
            //获取标签列表
            'edittag' => [
                'class' => 'mis\controllers\capacity\EdittagAction',
            ],
            //获取标签列表
            'select_menu' => [
                'class' => 'mis\controllers\capacity\SelectMenuAction',
            ],
            //获取二级分类选中的标签列表
            'select_tag' => [
                'class' => 'mis\controllers\capacity\SelectTagAction',
            ],
        ];
    }

}
