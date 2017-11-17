<?php 
namespace api\modules\v2_3_5\controllers;
use api\components\ApiBaseController;
/**
* 活动相关接口
*/
class ActivityController extends ApiBaseController
{
    public function behaviors()
    {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['qa_share'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            ],
            
        ];
    }
   
    public function actions()
    {
        return [
            //问答评论分享
            'qa_share' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\QaShareAction',
            ],
            //问答详情
            'qa' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\QaAction',
            ],
            //文章点赞
            'article_zan' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\ArticleZanAction',
            ],
            //活动文章详情
            'article' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\ArticleAction',
            ],
       		//活动列表
       		'list' => [
   				'class' => 'api\modules\v2_3_5\controllers\activity\ListAction',
       		],
            //问答评论详情
            'qa_cmt' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\QaCmtAction',
            ],
            //问答回复列表
            'qa_cmt_reply_list' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\QaCmtReplyListAction',
            ],
            //获取所有已回复的提问列表 
            'qa_has_reply_cmt' => [
                'class' => 'api\modules\v2_3_5\controllers\activity\QaHasReplyCmtAction',
            ],
        ];
    }   
    
}