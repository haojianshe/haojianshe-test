<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

class StatController extends MBaseController {

    //public $enableCsrfValidation = false;

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
            //显示帖子列表
            'tweet' => [
                'class' => 'mis\controllers\stat\TweetAction',
            ],
            'comment' => [
                'class' => 'mis\controllers\stat\CommentAction',
            ],
            //批改统计
            'correct' => [
                'class' => 'mis\controllers\stat\CorrectAction',
            ],
            //用户统计
            'user' => [
                'class' => 'mis\controllers\stat\UserAction',
            ],
            //用户统计
            'user_list' => [
                'class' => 'mis\controllers\stat\UserListAction',
            ],
            //学生老师用户关系统计
            'userrelation' => [
                'class' => 'mis\controllers\stat\UserRelationAction',
            ],
            //违规统计
            'violations' => [
                'class' => 'mis\controllers\stat\ViolationsAction',
            ],
            //查看单个老师违规统计列表
            'show_list' => [
                'class' => 'mis\controllers\stat\ShowListAction',
            ],
            //订单统计
            'order_list' => [
                'class' => 'mis\controllers\stat\OrderListAction',
            ], //订单详情
            'order_detail' => [
                'class' => 'mis\controllers\stat\OrderDetailAction',
            ],
            //批量获取用户设备号 判断是否用户是否统一设备注册
            'usertoken' => [
                'class' => 'mis\controllers\stat\UserTokensAction',
            ],
            //用户订单统计
            'order_user_list' => [
                'class' => 'mis\controllers\stat\OrderUserListAction',
            ], //内容订单统计
            'order_content_list' => [
                'class' => 'mis\controllers\stat\OrderContentListAction',
            ],
            //邀请统计
            'invite_list' => [
                'class' => 'mis\controllers\stat\InviteListAction',
            ],
            //打赏统计
            'reward_list' => [
                'class' => 'mis\controllers\stat\RewardListAction',
            ]
        ];
    }

}

?>