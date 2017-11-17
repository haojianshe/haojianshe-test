<?php

namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 联考活动管理
 */
class LkactivityController extends MBaseController {

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
            //联考活动列表
            'index' => [
                'class' => 'mis\controllers\lkactivity\IndexAction',
            ],
            //活动编辑
            'edit' => [
                'class' => 'mis\controllers\lkactivity\EditAction',
            ],
            //获取文章列表页面
            'articlelist' => [
                'class' => 'mis\controllers\lkactivity\ArticleAction',
            ],
            //获取问答列表页面
            'qa' => [
                'class' => 'mis\controllers\lkactivity\QaAction',
            ],
            //获取名师列表页面
            'ms' => [
                'class' => 'mis\controllers\lkactivity\MsAction',
            ],
            //删除
            'del' => [
                'class' => 'mis\controllers\lkactivity\DelAction',
            ],
            //状元分享会insert
            'qainsert' => [
                'class' => 'mis\controllers\lkactivity\QainsertAction',
            ],
            //状元分享会文章置顶
            'zhiding' => [
                'class' => 'mis\controllers\lkactivity\ZhidingAction',
            ],
            //编辑文章
            'contentlist' => [
                'class' => 'mis\controllers\lkactivity\ContentAction',
            ],
            //联考攻略文章录入
            'acinsert' => [
                'class' => 'mis\controllers\lkactivity\AcinsertAction',
            ],
            //联考攻略文章录入
            'article' => [
                'class' => 'mis\controllers\lkactivity\ArticleAction',
            ],
            //编辑模拟考
            'simulation' => [
                'class' => 'mis\controllers\lkactivity\SimulationAction',
            ],
            //报名详情
            'details' => [
                'class' => 'mis\controllers\lkactivity\DetailsAction',
            ],
            //删除考试卷
            'delpic' => [
                'class' => 'mis\controllers\lkactivity\DelpicAction',
            ],
            //评论列表页面
            'comments' => [
                'class' => 'mis\controllers\lkactivity\CommentsAction',
            ],
            //删除文章评论
            'delcomment' => [
                'class' => 'mis\controllers\lkactivity\DelcommentAction',
            ],
            //删除文章
            'delarticle' => [
                'class' => 'mis\controllers\lkactivity\DelarticleAction',
            ],
            //活动编辑
            'editarticle' => [
                'class' => 'mis\controllers\lkactivity\EditarticleAction',
            ],
            //
            'questions' => [
                'class' => 'mis\controllers\lkactivity\QuestionsAction',
            ],
            //编辑问答
            'editquestions' => [
                'class' => 'mis\controllers\lkactivity\EditquestionsAction',
            ],
            //删除批改记录
            'delquestions' => [
                'class' => 'mis\controllers\lkactivity\DelquestionsAction',
            ],
            //评论列表页面
            'qacomment' => [
                'class' => 'mis\controllers\lkactivity\QacommentAction',
            ],
            //模拟考试试卷城市列表
            'testsimulation' => [
                'class' => 'mis\controllers\lkactivity\SimulationlistAction',
            ],
            //模拟考试批卷城市详情
            'simulationdetail' => [
                'class' => 'mis\controllers\lkactivity\TestsimulationAction',
            ],
            //修改考试成绩
            'updatescore' => [
                'class' => 'mis\controllers\lkactivity\UpdatescoreAction',
            ],
            //统计考试成绩
            'statistical' => [
                'class' => 'mis\controllers\lkactivity\StatisticalAction',
            ],
              //整理数据
            'data' => [
                'class' => 'mis\controllers\lkactivity\DataAction',
            ],
        ];
    }

}
