<?php

namespace api\modules\v2_3_2\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\FavoriteFolderService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 取消收藏
 */
class CancelAction extends ApiBaseAction {

    public function run() {
        $tid = $this->requestParam('tid', true);
        $type = $this->requestParam('type', true);
        //查找
        $model = FavoriteService::findOne(['uid' => $this->_uid, 'tid' => $tid, 'type' => $type]);
        $folder = FavoriteFolderService::findOne(['folderid' => $model->folderid]);
        if ($folder) {
            $folder->fav_count =$folder->fav_count-1;
            $folder->save();
        }
        if ($model) {
            $model->delete();
            $redis = Yii::$app->cache;
            // 删除收藏用户缓存
            $redis->delete("content_fav_count_" . $tid . '_' . $type);
            $redis->delete("content_fav_users_new_" . $tid . '_' . $type);
            $redis->delete("userext_" . $uid);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }

}
