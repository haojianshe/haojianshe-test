<?php

namespace mis\controllers\vesttweet;

use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\components\MBaseAction;
use common\service\DictdataService;
use mis\service\LessonService;
use mis\service\UserService;
use mis\service\MisUserVestService;

/**
 * 发帖 
 */
class AddAction extends MBaseAction {

    /**
     * 发帖
     */
    public $resource_id = 'operation_vesttweet';

    public function run() {
        $mis_userid = Yii::$app->user->getIdentity()->mis_userid;
        //获取马甲用户
        $uids = MisUserVestService::getVestUser($mis_userid);
        $uid_array = explode(",", $uids);
        $user_infos = array();
        foreach ($uid_array as $key => $value) {
            $user_infos[] = UserService::findOne(["uid" => $value])->attributes;
        }

        $request = Yii::$app->request;
        // 图片分类
        $config['imgmgr_level_1'] = DictdataService::getTweetMainType();
        $config['imgmgr_level_2'] = DictdataService::getTweetSubType();
        $msg = '';
        $isclose = false;
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $tid = $request->get('tid');
            if ($tid) {
                //edit
                if (!is_numeric($tid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = TweetService::findOne(['tid' => $tid]);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config]);
            } else {
                //add
                $model = new TweetService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'catalog' => $config, "users" => $user_infos]);
            }
        } else {
            $model = new TweetService();
            $model->load($request->post());
            $model->ctime = time();
            $model->utime = time();
            //添加一级和二级分类id
            $model->f_catalog_id = null;
            $model->s_catalog_id = null;
            if ($model->f_catalog) {
                $model->f_catalog_id = DictdataService::getTweetMainTypeIdByName($model->f_catalog);
                if ($model->s_catalog) {
                    $model->s_catalog_id = DictdataService::getTweetSubTypeIdByName($model->f_catalog_id, $model->s_catalog);
                }
            }
            //用户提交
            $model->lessonid = $request->post('TweetService')['lessonid'];
            if (!empty($model->lessonid)) {
                $lessoninfo = LessonService::findOne(['lessonid' => $model->lessonid, 'status' => 0]);
                
                if (empty($lessoninfo)) {
                    die('推荐步骤图不存在 或已被删除');
                }
                if ($lessontype) {
                    TweetService::tweetRecLessonPushMsg(1, $model->uid, $request->post('TweetService')['tid']);
                }
            }
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }

            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config, "users" => $user_infos]);
        }
    }

}
