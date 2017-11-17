<?php

namespace api\modules\v2_3_7\controllers;

use api\components\ApiBaseController;

/**
 * 出版社相关接口
 */
class PublishingController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            /*'login' => [
                'class' => 'api\components\filters\LoginFilter',
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            ],*/
        ];
    }

    public function actions() {
        return [
            //出版社个人中心顶部广告
            'top_adv' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\TopAdvAction',
            ],
             //分类图书推荐广告（个人中心图书 素材 能力模型素材下推荐） 
            'publishing_books_adv' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\PublishingBooksAdvAction',
            ],
            //图书信息
            'book_info' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\BookInfoAction',
            ],
            //出版社图书列表（分享信息）
            'publishing_books' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\PublishingBooksAction',
            ],
            //美院帮推荐图书列表
            'myb_recommend_books' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\MybRecommendBooksAction',
            ],
             //美院帮推荐图书分类
            'myb_recommend_book_class' => [
                'class' => 'api\modules\v2_3_7\controllers\publishing\MybRecommendBookClassAction',
            ],
        ];
    }

}
