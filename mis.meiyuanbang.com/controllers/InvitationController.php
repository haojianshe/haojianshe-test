<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 邀请活动
 */
class InvitationController extends MBaseController {

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
            //显示邀请活动列表
            'index' => [
                'class' => 'mis\controllers\invitation\IndexAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\invitation\DelAction',
            ],
            //添加或者修改用户
            'edit' => [
                'class' => 'mis\controllers\invitation\EditAction',
            ],
            //奖品列表
            'prize_list' => [
                'class' => 'mis\controllers\invitation\PrizeListAction',
            ],
            //缩略图上传
            'cthumbupload' => [
                'class' => 'mis\controllers/invitation/AthumburlAction',
            ],
            //缩略图上传
            'thumbupload' => [
                'class' => 'mis\controllers\invitation\ThumbUploadAction',
            ],
            //奖品修改
            'prize_edit' => [
                'class' => 'mis\controllers\invitation\PrizeEditAction',
            ],
            //邀请记录
            'invitation_record' => [
                'class' => 'mis\controllers\invitation\InvitationRecordAction',
            ],
             //领取记录
            'award_record' => [
                'class' => 'mis\controllers\invitation\AwardRecordAction',
            ],
             //领取记录
            'award_edit' => [
                'class' => 'mis\controllers\invitation\AwardEditAction',
            ],
            //领取记录
            'tweet' => [
                'class' => 'mis\controllers\invitation\TweetAction',
            ],
            //领取记录
            'city' => [
                'class' => 'mis\controllers\invitation\CityAction',
            ],
            
        ];
        
    }

}
