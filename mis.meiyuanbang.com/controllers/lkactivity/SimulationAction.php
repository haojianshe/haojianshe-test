<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\LkArticleService;

/**
 * 编辑联考活动
 */
class SimulationAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        $admin['admin'] = $this->getAdminData();
        $lkid['lkid'] = $request->get('lkid');
        $newsid = $request->post('newsid');
        if (!$request->isPost) {
            //处理get请求
            $ret['data'] = $this->getHandle($request->get('lkid'));
           # print_r($ret);
        } else {
            //处理post请求
            $ret = $this->postHandle($request->post('lkidname'), $newsid);
        }
        return $this->controller->render('simulation', ['model' => $ret, 'admin' => $admin, 'lkid' => $lkid]);
    }

    /**
     * 处理访问,判断数据提取或显示空白页面
     */
    private function getHandle($lkid) {
        $request = Yii::$app->request;
        //判断参数
        $newsid = self::getNewsId($lkid);
        if ($newsid) {
            $lkidArray = LkArticleService::findOne(['lkid' => $lkid]);
            $newsArray = NewsService::findOne(['newsid' => $newsid]);
            $newsDataArray = NewsDataService::findOne(['newsid' => $newsid]);
            $ret = [
                'start_time' => $lkidArray->start_time,
                'newsid' => $lkidArray->newsid,
                'end_time' => $lkidArray->end_time,
                'title' => $newsArray->title,
                'keywords' => $newsArray->keywords,
                'desc' => $newsArray->desc,
                'teacher_id' => $lkidArray->teacher_id,
                'content' => $newsDataArray->content,
                'signup_limit' => $lkidArray->signup_limit
            ];
        } else {
            //新添加
            $lkid_id = 0;
            $ret = $this->getRetModel($lkid_id);
        }
        return $ret;
    }

    /*
     * 查看是否有联考活动数据
     */

    public static function getNewsId($lkid) {
        $lkData = LkArticleService::findOne(['lkid' => $lkid]);
        return $lkData->newsid;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle($lkid, $news_id) {
        $request = Yii::$app->request;
        //先获取model
        $model = new NewsService();
        $newData = new NewsDataService();
        $lkData = LkArticleService::findOne(['lkid' => $lkid]);
        if ($news_id) {
            $lkid_id = $request->post('lkid_id');
            $model = NewsService::findOne(['newsid' => $news_id]);
            $newData = NewsDataService::findOne(['newsid' => $news_id]);
        } else {
            $lkid_id = 0;
        }
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $model->load($request->post());
        $model->title = $request->post('title');
        $model->keywords = $request->post('keywords');
        $model->desc = $request->post('desc');
        $model->status = 0;
        $model->listorder = 0;
        $model->utime = 0;
        $model->thumb = '0';
        $model->username = $usermodel->mis_realname;
        $model->ctime = time();
        $model->catid = 3;
        if ($model->save()) {
            if (!$news_id) {
                $newData->newsid = $model->attributes['newsid'];
            }
            $newData->content = $request->post('content');
            $newData->save();
        }

        $lkData->signup_limit = $request->post('signup_limit');
        $lkData->teacher_id = $request->post('teacher_id');
        $lkData->end_time = strtotime($request->post('end_time'));
        $lkData->start_time = strtotime($request->post('start_time'));
        $lkData->newsid = $model->attributes['newsid'];
        if ($lkData->save()) {
            //修改完毕情况缓存
            if ($request->post('isedit') == 1) {
                $redis = Yii::$app->cache;
                $rediskey = "lk_" . $lkid;
                $redis->delete($rediskey);
            }
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = false;
        }
        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($lkid_id) {
        if ($lkid_id == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newmodel = LkActivityService::findOne(['lkid' => $lkid_id]);
            $ret['model'] = $newmodel;
        }
        return $ret;
    }

    /*
     * 获取管理员所有数据
     */

    private function getAdminData() {
        $sql = "select mis_userid,mis_realname,mis_username from myb_mis_user where find_in_set('19',roleids)";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        return $command_count->queryAll();
    }

}
