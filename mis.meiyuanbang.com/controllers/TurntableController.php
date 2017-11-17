<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 抽奖活动管理
 */
class TurntableController extends MBaseController {

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
                'class' => 'mis\controllers\turntable\IndexAction',
            ],
            //添加或者修改用户
            'edit' => [
                'class' => 'mis\controllers\turntable\EditAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\turntable\DelAction',
            ],
            //显示活动列表
            'rewardlist' => [
                'class' => 'mis\controllers\turntable\RewardlistAction',
            ],
            //显示活动列表
            'userlist' => [
                'class' => 'mis\controllers\turntable\UserlistAction',
            ],
            //显示活动列表
            'prizegame' => [
                'class' => 'mis\controllers\turntable\PrizegameAction',
            ],
            //活动列表修改
            'prizeedit' => [
                'class' => 'mis\controllers\turntable\PrizeeditAction',
            ],
            //刪除活动列表
            'prizedel' => [
                'class' => 'mis\controllers\turntable\PrizedelAction',
            ],
            //展示预览
            'prizeshow' => [
                'class' => 'mis\controllers\turntable\PrizeshowAction',
            ],
        ];
    }

}
