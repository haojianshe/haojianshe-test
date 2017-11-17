<?php

namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\UserService;
/**
 * 后台邀请统计
 * 
 */
class InviteListAction extends MBaseAction {

    public $resource_id = 'operation_stat';

    const USER_TIME = 5;

    public function run() {

        $request = Yii::$app->request;
        $search_con['start_time'] = '';
        $search_con['end_time'] = '';
        $search_con['user_type'] = '';
        $search_con['user'] = '';
        $search_con['app'] = '';
        $where = '';

        //首次进入返回空数据
        if (empty($_REQUEST['search'])) {
            $data = [];
            return $this->controller->render('invite', $data);
        }

        //用户类型 1用户电话 2用户名
        $search_con['user_type'] = $request->get('user_type');
        if (empty($search_con['user_type']) || !isset($search_con['user_type'])) {
            $search_con['user_type'] = 1;
        }
        $search_con['user'] = $request->get('user');
        //如果有用户搜索信息,按照条件来获取信息
        if ($search_con['user']) {
            $userInfo = UserService::getUserInfo($search_con['user'], $search_con['user_type']);
            if ($userInfo) {
                $array = []; #用户uid
                foreach ($userInfo as $key => $val) {
                    $array[$key] = $val['uid'];
                }
            }
        }

        // 生效开始时间
        $search_con['start_time'] = $request->get('start_time');
        // 生效结束时间
        $search_con['end_time'] = $request->get('end_time');
        if (empty($search_con['start_time'])) {
            $search_con['start_time'] = date("Y-m-d 00:00:00", strtotime("-1 weeks"));
        }
        if (empty($search_con['end_time'])) {
            $search_con['end_time'] = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }

        //被邀请人APP使用时长
        $search_con['app'] = $request->get('app');
        if (empty($search_con['app']) || !isset($search_con['app'])) {
            $search_con['app'] = 0;
        }
        $search_con['invite'] = $request->get('invite');
        if (empty($search_con['invite']) || !isset($search_con['invite'])) {
            $search_con['invite'] = 0;
        }

        if ($search_con['user']) {
            if (empty($array)) {
                $data['search'] = $search_con;
                return $this->controller->render('invite', $data);
            }
        }
        //获取数据
        $data['models'] = UserService::getInviteInfo($search_con['start_time'], $search_con['end_time'], $array, $search_con['app'], $search_con['user']);
        $data['search'] = $search_con;
        //如果数据存在获取用户的手机号
        if ($data) {
            foreach ($data['models']['models'] as $key => $val) {
                $data['models']['models'][$key]['city'] = self::getTelApi($val['umobile']);
                $data['models']['models'][$key]['name'] = UserService::getInfoByUids($val['invite_userid']);
            }
        }
        return $this->controller->render('invite', $data);
    }

    /**
     * 获取用户电话归属地
     */
    public static function getTelApi($mobile = '') {
        $rediskey = "tel_" . $mobile;
        $redis = Yii::$app->cache;
        $tel_key = $redis->get($rediskey);
        if (empty($tel_key)) {
            $url = "http://sj.apidata.cn/?mobile=" . $mobile; //api接口地址
            $res = self::request_get($url);
            $res_arr = json_decode($res, true);
            if ($res_arr['status'] == 1) {  //如果成功获取数据
                $tel_key['province'] = $res_arr['data']['province'];
                $tel_key['city'] = $res_arr['data']['city'];
            }
            if ($res_arr) {
                $redis->set($rediskey, $tel_key);
                $redis->expire($rediskey, 3600 * 24 * 30);
            }
        }
        return $tel_key;
    }

    //获取被邀请人手机所在地
    public static function request_get($url = '') {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
