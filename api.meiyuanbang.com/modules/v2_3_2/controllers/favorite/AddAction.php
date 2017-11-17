<?php

namespace api\modules\v2_3_2\controllers\favorite;

use Yii;
use api\components\ApiBaseAction;
use api\service\FavoriteService;
use api\service\FavoriteFolderService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 收藏
 */
class AddAction extends ApiBaseAction {

    public function run() {
        $tid = $this->requestParam('tid', true);
        $type = $this->requestParam('type', true);
        //画夹id
        $folderid = $this->requestParam('folderid');
        if (!$folderid) {
            $folderid = $this->setFavFolder($this->_uid);
        }
        //判断是否已经收藏
        $model = FavoriteService::findOne(['uid' => $this->_uid, 'tid' => $tid, 'type' => $type]);
        if ($model) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }
        //添加记录
        $model = new FavoriteService();
        $model->tid = $tid;
        $model->type = $type;
        $model->folderid = $folderid;
        $model->uid = $this->_uid;
        $model->ctime = time();
        $ret = $model->save();
        if ($folderid) {
            $folder = FavoriteFolderService::findOne(['folderid' => $folderid]);
            if ($folder) {
                $folder->fav_count = $folder->fav_count + 1;
                $folder->save();
            }
        }
        // 删除收藏用户缓存
        $redis = Yii::$app->cache;
        $redis->delete("content_fav_count_" . $model->attributes['tid'] . '_' . $type);
        $redis->delete("content_fav_users_new_" . $model->attributes['tid'] . '_' . $type);
        $redis->delete("userext_" . $uid);
        if ($ret) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
    }

    
    //插入数据
    private function setFavFolder($uid = '') {
        //老版本 
        $favRes = FavoriteFolderService::findOne(['uid' => $uid]);
        if ($favRes) {
            $folderid = $favRes->folderid;
        } else {
            $favNewRes = new FavoriteFolderService();
            $favNewRes->uid = $uid;
            $favNewRes->ctime = time();
            $favNewRes->utime = time();
            $favNewRes->name = '未分类';
            if ($favNewRes->save()) {
                $folderid = $favNewRes->attributes['folderid'];
            }
        }
        return $folderid;
    }

}
