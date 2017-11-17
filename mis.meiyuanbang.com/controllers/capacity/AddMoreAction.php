<?php

namespace mis\controllers\capacity;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\DictdataService;
use common\service\dict\CapacityModelDictDataService;
use mis\service\CapacityModelMaterialService;

#马甲用户
use mis\service\UserService;
use mis\service\MisUserVestService;

/**
 * 批量增加能力素材页面
 */
class AddMoreAction extends MBaseAction {

    /**
     * 批量增加能力素材
     */
    public function run() {
        $request = Yii::$app->request;
        $classtype['maintype'] = CapacityModelDictDataService::getCorrectMainType();
        $classtype['subtype'] = CapacityModelDictDataService::getCorrectSubType();
        $classtype['captype'] = CapacityModelDictDataService::getCorrectScoreItem();
        $mis_userid = Yii::$app->user->getIdentity()->mis_userid;
        //获取马甲用户
        $uids = MisUserVestService::getVestUser($mis_userid);
        $uid_array = explode(",", $uids);
        $user_infos = array();
        foreach ($uid_array as $key => $value) {
            $user_infos[] = UserService::findOne(["uid" => $value])->attributes;
        }

        $model = new CapacityModelMaterialService();
        return $this->controller->render('addmore', ['model' => $model, 'msg' => '', 'classtype' => json_encode($classtype), "users" => $user_infos]);
    }

}
