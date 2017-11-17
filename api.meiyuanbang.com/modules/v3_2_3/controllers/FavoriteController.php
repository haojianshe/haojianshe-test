<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 收藏夹相关接口
 */
class FavoriteController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['folder_list', 'folder_add', 'folder_update', 'folder_del', 'folder_change'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                /*'only' => ['']*/
            ],
        ];
    }

    public function actions() {
        return [
            //获取用户画夹名称列表
            'folder_list' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderListAction',
            ],
            //新建画夹
            'folder_add' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderAddAction',
            ],
            //修改画夹
            'folder_update' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderUpdateAction',
            ],
            //删除画夹
            'folder_del' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderDelAction',
            ],
            //批量更改画夹内容
            'folder_change' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderChangeAction',
            ],
            //用户画夹列表
            'folder_list_user' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderListUserAction',
            ],
            //画夹信息
            'folder_info' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderInfoAction',
            ],
            //收藏过内容的画夹列表
            'folder_list_content' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FolderListContentAction',
            ],
            //收藏图片列表
            'favorite_list_img' => [
                'class' => 'api\modules\v3_2_3\controllers\favorite\FavoriteListImgAction',
            ]
        ];
    }

}
