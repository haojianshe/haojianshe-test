<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

//画室
class StudioController extends MBaseController {

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

    public function actions() {
        return [
            //列表
            'index' => [
                'class' => 'mis\controllers\studio\IndexAction',
            ],
            //编辑
            'edit' => [
                'class' => 'mis\controllers\studio\EditAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\studio\DelAction',
            ],
            'add' => [
                'class' => 'mis\controllers\studio\AddAction',
            ],
            //选择批改老师
            'teachersel' => [
                'class' => 'mis\controllers\studio\TeacherSelAction',
            ],
            //广告位列表
            'advertlist' => [
                'class' => 'mis\controllers\studio\AdvertlistAction',
            ],
            //广告位编辑、添加
            'editadvert' => [
                'class' => 'mis\controllers\studio\EditadvertAction',
            ],
            //删除画室广告
            'deladvert' => [
                'class' => 'mis\controllers\studio\DeladvertAction',
            ],
            //用户个人中心菜单列表
            'userlist' => [
                'class' => 'mis\controllers\studio\UserlistAction',
            ],
            //用户个人中心排序
            'listorder' => [
                'class' => 'mis\controllers\studio\ListorderAction',
            ],
            //班型列表
            'class_list' => [
                'class' => 'mis\controllers\studio\ClassListAction',
            ],
            //班型编辑
            'class_edit' => [
                'class' => 'mis\controllers\studio\ClassEditAction',
            ],
            'thumbupload' => [
                'class' => 'mis\controllers\studio\ThumbUploadAction',
            ],
            'updateclass' => [
                'class' => 'mis\controllers\studio\UpdateclassAction',
            ],
            'editcontent' => [
                'class' => 'mis\controllers\studio\EditcontentAction',
            ],
            //地址管理 编辑
            'editaddress' => [
                'class' => 'mis\controllers\studio\EditaddressAction',
            ],
            //地址管理 列表
            'editaddress_list' => [
                'class' => 'mis\controllers\studio\EditaddressListAction',
            ],
            //章节视频列表
            'del_address' => [
                'class' => 'mis\controllers\studio\DelAddressAction',
            ],
            //个人中心管理 =>添加页面
            'addpage' => [
                'class' => 'mis\controllers\studio\AddpageAction',
            ],
            //个人中心管理，添加页面，数据写入
            'pageinsert' => [
                'class' => 'mis\controllers\studio\PageinsertAction',
            ],
            //报名方式列表
            'signlist' => [
                'class' => 'mis\controllers\studio\SignlistAction',
            ],
            //报名方式列表
            'signedit' => [
                'class' => 'mis\controllers\studio\SigneditAction',
            ],
            //删除单个报名方式
            'delsign' => [
                'class' => 'mis\controllers\studio\DelSignAction',
            ],
            //报名详情
            'signdetail' => [
                'class' => 'mis\controllers\studio\SignDetailAction',
            ],
            //页面管理列表
            'menumanage' => [
                'class' => 'mis\controllers\studio\MenuManageAction',
            ],
            //文章排序
            'articlelistorder' => [
                'class' => 'mis\controllers\studio\ArticleListorderAction',
            ],
            //文章编辑
            'editarticle' => [
                'class' => 'mis\controllers\studio\EditArticleAction',
            ],
            //文本编辑
            'edittext' => [
                'class' => 'mis\controllers\studio\EditTextAction',
            ],
            //老师管理
            'teacherlist' => [
                'class' => 'mis\controllers\studio\TeacherListAction',
            ],
            //老师管理写入
            'teacherinsert' => [
                'class' => 'mis\controllers\studio\TeacherInsertAction',
            ],
            //作品写入
            'opuslist' => [
                'class' => 'mis\controllers\studio\OpusListAction',
            ],
            //上传图片
            'pic_upload' => [
                'class' => 'mis\controllers\studio\PicUploadAction',
            ],
            //作品修改
            'editopus' => [
                'class' => 'mis\controllers\studio\EditopusAction',
            ],
            //删除作品
            'del_opus' => [
                'class' => 'mis\controllers\studio\DelOpusAction',
            ],
            
             'thumbopus' => [
                'class' => 'mis\controllers\studio\ThumbOpusAction',
            ],
            //报名详情
             'sign_detail' => [
                'class' => 'mis\controllers\studio\SignDetailAction',
            ],
            
            
            
        ];
    }

}
