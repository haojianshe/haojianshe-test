<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 专题管理
 */
class MaterialController extends MBaseController {

    public $enableCsrfValidation = false;

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

    public function actions() {

        return [
            //专题列表
            'index' => [
                'class' => 'mis\controllers\material\IndexAction',
            ],
            //专题编辑
            'edit' => [
                'class' => 'mis\controllers\material\EditAction',
            ],
            //删除专题
            'del' => [
                'class' => 'mis\controllers\material\DelAction',
            ],
            //专题图片选择
            'sel' => [
                'class' => 'mis\controllers\material\SelAction',
            ],
            //排序
            'sort' => [
                'class' => 'mis\controllers\material\SortAction',
            ],
             'insert' => [
                'class' => 'mis\controllers\material\InsertAction',
            ],
            
        ];
    }

}
