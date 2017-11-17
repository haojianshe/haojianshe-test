<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use api\service\UserService;
use api\service\UserDetailService;
use api\service\UserCoinService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\Resource;
use mis\service\TeamInfoService;
use mis\service\TeamMemberService;
use mis\service\UserService as MisUserService;

/**
 * 用户休息录入/编辑
 */
class SetUserAction extends MBaseAction {

    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        #昵称
        $sname = $request->post('sname');
        $thumb = $request->post('thumb'); //用户头像
        $password = $request->post('pass_word'); //用户密码
        $intro = $request->post('intro'); //用户介绍
        $umobile = $request->post('umobile'); //用户手机号
        $uid = $request->post('uid'); //用户手机号
        if ($uid > 0) {
            $userData = UserService::findOne(['id' => $uid]);
            $userArray = $userData->attributes;
            if ($userArray['umobile'] != $umobile) {
                #判断手机号是否存在
                $mobile_user = UserService::findOne(['umobile' => trim($umobile)]);
                if ($mobile_user) {
                    echo 8031;
                    exit;
                }
            }
            $userDetail = UserDetailService::findOne(['uid' => $uid]);
            $userDetail->intro = $intro;
            $userDetail->save();
            $userDetailArray = $userDetail->attributes;
            if ($userDetailArray['sname'] != $sname) {
                #判断昵称是否存在
                $mobile_detail_sname = UserDetailService::findOne(['sname' => trim($sname)]);
                if ($mobile_detail_sname) {
                    echo 8022;
                    exit;
                }
            }
        }
        if ($uid == 0) {
            #判断手机号是否存在
            $mobile_user = UserService::findOne(['umobile' => trim($umobile)]);
            if ($mobile_user) {
                echo 803;
                exit;
            }
            #判断昵称是否存在
            $mobile_detail = UserDetailService::findOne(['sname' => trim($sname)]);
            if ($mobile_detail) {
                echo 802;
                exit;
            }
        }
        //没有昵称
        if (empty($sname)) {
            $sname = substr($umobile, 0, 4) . "***" . substr($umobile, -4);
        }

        //查询头像
        if ($thumb != 1) {
            $img = Resource::findOne(['rid' => $thumb]);
            $avatar = $img->img;
        }
        if (!empty($_SESSION['img_json'])) {
            $avatar = $_SESSION['img_json'];
        } else {
            $avatar = $userDetail->avatar;
        }
        echo self::newUser($umobile, $password, $sname, $avatar, '', '', '', '', '', $intro, $uid, 1);
        exit;
    }

    /**
     * 
     * @param type $umobile
     * @param type $password
     * @param type $sname
     * @param type $avatar
     * @param type $oauth_type
     * @param type $oauth_key
     * @param type $unionid
     * @param type $genderid
     * @param type $qd
     * @param type $intro
     * @return type
     */
    static function newUser($umobile, $password, $sname = NULL, $avatar = NULL, $oauth_type = NULL, $oauth_key = NULL, $unionid = NULL, $genderid = NULL, $qd = null, $intro = null, $uid = 0, $type = 0) {
        unset($_SESSION['img_json']);
        if (!$uid) {
            $model = new UserService();
            $usercoin = new UserCoinService();
            $user_detail = new UserDetailService();
        } else {
            $model = UserService::findOne(['id' => $uid]);
            $usercoin = UserCoinService::findOne(['uid' => $uid]);
            $user_detail = UserDetailService::findOne(['uid' => $uid]);
        }

        //插入user表
        if ($oauth_type) {
            $model->oauth_type = $oauth_type;
            $model->oauth_key = $oauth_key;
            if ($oauth_type == "weixin") {
                $model->unionid = $unionid;
            }
            $model->login_type = 1;
        } else {
            $model->login_type = 0;
        }
        $model->umobile = $umobile;
        if ($password) {
            $model->pass_word = md5($password);
        }
        $model->pass_mark = "00";
        $model->register_status = 0;
        $model->create_time = time();
        if ($qd) {
            $model->qd = $qd;
        }
        $user_ret = $model->save();

        //判断用户以前是否已经添加过小组信息
        $teammodel = TeamInfoService::findOne(['uid' => $model->attributes['id']]);
        if (!$teammodel) {
            //第一次设置为殿堂老师需要添加小组初始数据
            $teammodel = new TeamInfoService();
            $teammodel->uid = $model->attributes['id'];
            $teammodel->teamname = $sname . '的小组';
            $teammodel->membercount = 1;
            $teammodel->ctime = time();
            $teammodel->save();
            //自己加入小组
            $teammember = new TeamMemberService();
            $teammember->teamid = $teammodel->attributes['teamid'];
            $teammember->uid = $model->attributes['id'];
            $teammember->addtime = time();
            $teammember->isadmin = 2;
            $teammember->save();
            //清除殿堂老师和用户信息缓存
            MisUserService::removecache($model->attributes['id']);
            MisUserService::remove_famousteacher_cache();
        }

        if ($user_ret) {
            //插入userdetail 表
            $user_detail->uid = $model->attributes['id'];
            if (empty($sname)) {
                $user_detail->sname = self::randomSname($sname);
            } else {
                $user_detail->sname = $sname;
            }


            $user_detail->genderid = $genderid;
            $user_detail->intro = $intro;
            $user_detail->role_type = 2;
            if ($type == 1) {
                $user_detail->avatar = $avatar;
            }
            $user_detail->save();
            $userdetail_ret = $user_detail->save();
            if ($userdetail_ret) {
                //新用户第一次登陆，在金币表添加一条记录
                $usercoin->uid = $user_detail->uid;
                $usercoin->gradeid = 1;
                $usercoin->total_coin = 0;
                $usercoin->remain_coin = 0;
                $usercoin_ret = $usercoin->save();
                if ($usercoin_ret) {
                    $model->register_status = 0;
                    $user_ret = $model->save();
                } else {
                    $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                }
            }
            #else {
            #   $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
            #}
        } else {
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
        return $user_detail->uid;
    }

    /**
     * 判断姓名是否重复若重复生成一个带数字后缀的用户名
     * @param  [type] $sname [description]
     * @return [type]        [description]
     */
    static function randomSname($sname) {
        $new_name = $sname;
        $max_seed = 10000;
        $min_seed = 0;
        $counter = 0;
        while (NULL !== UserDetailService::findOne(["sname" => $new_name])) {
            if ($counter >= 10) {
                $min_seed = $max_seed;
                $max_seed *= 10;
                $counter = 0;
            }
            $new_name = $sname . '_' . strval(rand($min_seed, $max_seed - 1));
            $counter++;
        }
        return $new_name;
    }

}
