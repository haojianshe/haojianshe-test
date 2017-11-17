<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 直播课管理
 */
class LiveController extends MBaseController {

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
                'except' => ['notice'],
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
                'except' => ['notice'],
            ],
        ];
    }

    /**
     * action集合 
     */
    public function actions() {
        return [
            //显示直播课列表
            'index' => [
                'class' => 'mis\controllers\live\IndexAction',
            ],
            //添加或者修改直播列表
            'edit' => [
                'class' => 'mis\controllers\live\EditAction',
            ],
            //缩略图上传
            'cthumbupload' => [
                'class' => 'mis\controllers\live\CThumbUploadAction',
            ],
            //缩略图上传
            'ccthumbupload' => [
                'class' => 'mis\controllers\live\CcThumbUploadAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\live\DelAction',
            ],
            //推荐列表
            'recommendlist' => [
                'class' => 'mis\controllers\live\RecommendlistAction',
            ],
            //推荐列表
            'addrecommend' => [
                'class' => 'mis\controllers\live\AddrecommendAction',
            ],
            //删除推荐
            'delrem' => [
                'class' => 'mis\controllers\live\DelremAction',
            ],
            //推荐排序
            'editrem' => [
                'class' => 'mis\controllers\live\EditremAction',
            ],
            //直播课推荐添加
            'reminsert' => [
                'class' => 'mis\controllers\live\ReminsertAction',
            ],
            //直播课推荐添加
            'show_url' => [
                'class' => 'mis\controllers\live\ShowUrlAction',
            ],
            //阿里云直播转录播通知
            'notice' => [
                'class' => 'mis\controllers\live\NoticeAction',
            ],
             //
            'select_menu' => [
                'class' => 'mis\controllers\live\SelectMenuAction',
            ],
             //
            'obtain' => [
                'class' => 'mis\controllers\live\ObtainAction',
            ],
             //选择批改老师
            'teachersel' => [
                'class' => 'mis\controllers\live\TeacherSelAction',
            ],
             //IOS价格选择
             'ios_price_sel' => [
                'class' => 'mis\controllers\live\IosPriceSelAction',
            ],
            'recording_ios_price_sel' => [
                'class' => 'mis\controllers\live\RecordingIosPriceSelAction',
            ],
            
             'lesson' => [
                'class' => 'mis\controllers\live\LessonAction',
            ],
            
            
            
            
            
            
        ];
    }

}
