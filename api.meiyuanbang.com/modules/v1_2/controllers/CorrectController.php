<?php
namespace api\modules\v1_2\controllers;
use api\components\ApiBaseController;
/**
* 
*/
class CorrectController extends ApiBaseController
{
    public function behaviors()
    {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                //'only' => ['submit','reward','finish','get_user_correct','get_teacher_correct','update_status','teacherrecommend'],
            		'only' => ['submit','reward','finish','get_user_correct','get_teacher_correct','update_status'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['get_correct_detail','teacher_list','user_wait_correct_list','user_has_correct_list','teacher_has_correct_list','teacher_wait_correct_list'],
            ],
            'black' => [
                'class' => 'api\components\filters\BlackFilter',
                'only' => ['submit'],
            ],
        ];
    }
   
    public function actions()
    {
        return [
            //上传图片
            'upload_pic' => [
                'class' => 'api\modules\v1_2\controllers\correct\UploadPicAction',
            ],
            //上传网页批改图片（返回js用于处理图片）
            'upload_pic_h5' => [
                'class' => 'api\modules\v1_2\controllers\correct\UploadPicHAction',
            ],
            //上传语音
            'upload_talk' => [
                'class' => 'api\modules\v1_2\controllers\correct\UploadTalkAction',
            ],
            //提交批改
            'submit' => [
                'class' => 'api\modules\v1_2\controllers\correct\SubmitAction',
            ],
            //批改打赏
            'reward' => [
                'class' => 'api\modules\v1_2\controllers\correct\RewardAction',
            ],
            //完成批改
            'finish' => [
                'class' => 'api\modules\v1_2\controllers\correct\FinishAction',
            ],
            //学生批改列表
            'get_user_correct' => [
                'class' => 'api\modules\v1_2\controllers\correct\UserGetlistAction',
            ],
            //批改老师批改列表
            'get_teacher_correct' => [
                'class' => 'api\modules\v1_2\controllers\correct\TeacherGetlistAction',
            ],
            //红笔批改老师列表（包含选择）
            'teacher_list' => [
                'class' => 'api\modules\v1_2\controllers\correct\TeacherListAction',
            ],
            //更改批改老师状态 是否接收批改  0正常 1删除 2暂不接受批改
            'update_status' => [
                'class' => 'api\modules\v1_2\controllers\correct\UpdateStatusAction',
            ],
            //批改详情页
            'get_correct_detail' => [
                'class' => 'api\modules\v1_2\controllers\correct\CorrectDetailAction',
            ],
            //批改老师信息
            'get_correct_teacher_info' => [
                'class' => 'api\modules\v1_2\controllers\correct\CorrectTeacherInfoAction',
            ],
             //老师等待批改列表
            'teacher_wait_correct_list' => [
                'class' => 'api\modules\v1_2\controllers\correct\TeacherWaitCorrectAction',
            ],
            //老师已批改列表
            'teacher_has_correct_list' => [
                'class' => 'api\modules\v1_2\controllers\correct\TeacherHasCorrectAction',
            ],
            //学生等待批改列表
            'user_wait_correct_list' => [
                'class' => 'api\modules\v1_2\controllers\correct\UserWaitCorrectAction',
            ],
            //学生已批改列表
            'user_has_correct_list' => [
                'class' => 'api\modules\v1_2\controllers\correct\UserHasCorrectAction',
            ],
       		//推荐批改老师
       		'teacherrecommend' => [
   				'class' => 'api\modules\v1_2\controllers\correct\TeacherRecommendAction',
       		],
        ];
    }   
    
}
