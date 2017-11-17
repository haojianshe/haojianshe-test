<?php

namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 专题页接口
 */
class MaterialController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
               /* 'only' => []*/
            ],
        ];
    }

    public function actions() {
        return [
            //获取最新
            'get_material_subject' => [
                'class' => 'api\modules\v3_0_2\controllers\material\GetMaterialSubjectAction',
            ],
            //素材筛选
            'get_material_tag' => [
                'class' => 'api\modules\v3_0_2\controllers\material\GetMaterialTagAction',
            ],
            //详情
            'get_subject_detail' => [
                'class' => 'api\modules\v3_0_2\controllers\material\GetSubjectDetailAction',
            ],
            //顶部广告
            'top_adv' => [
                'class' => 'api\modules\v3_0_2\controllers\material\TopAdvAction',
            ],
            //顶部专题分类
            'get_subject_category' => [
                'class' => 'api\modules\v3_0_2\controllers\material\GetSubjectCategoryAction',
            ],
            //素材专题点赞接口
            'get_subject_support' => [
                'class' => 'api\modules\v3_0_2\controllers\material\GetSubjectSupportAction',
            ]
        ];
    }

}
