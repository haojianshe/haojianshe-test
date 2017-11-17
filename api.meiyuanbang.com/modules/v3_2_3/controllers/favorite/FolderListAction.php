<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;

/**
 * 获取用户画夹名称列表
 */
class FolderListAction extends ApiBaseAction {

    public function run() {
        //用户token中获取用户id
        $uid = $this->_uid;
        $favFolderData = FavoriteFolderService::getUserFavFoldList($uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $favFolderData);
    }

}
