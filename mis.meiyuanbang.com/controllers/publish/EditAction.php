<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\ActivityService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 活动添加和修改页面
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        if (isset($uid)) {
            $ret = $this->getHandle($uid);
            return $this->controller->render('edit', ['usermodel' => $ret]);
        } else {
            return $this->controller->render('edit');
        }
    }

    function getHandle($uid) {
        $user = (new \yii\db\Query())
                ->select(['cud.sname', 'cud.avatar', 'cud.intro', 'cud.uid', 'cu.pass_word', 'cu.umobile'])
                ->from('ci_user_detail as cud')
                ->innerJoin('ci_user as cu', 'cud.uid=cu.id')
                ->where(['cu.id' => $uid])
                ->andWhere(['cud.role_type' => 2])
                ->one();
        $arr = json_decode($user['avatar'], 1);
        $user['avatar'] = $arr['img']['s']['url'];
        $user['rid'] = 1;
        return $user;
    }

}
