<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;

/**
 * 新建画夹
 */
class FolderDelAction extends ApiBaseAction {

    public function run() {
        //用户token中获取用户id
        $uid =$this->_uid;
        $folderid = $this->requestParam('folderid', true);
        //如果不存在不能删除
        if (FavoriteFolderService::DelUserFavFolder($uid, $folderid) == false) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }

}
