<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;
use api\service\FavoriteService;


/**
 * 用户画夹列表
 */
class FolderListUserAction extends ApiBaseAction {

    public function run() {
        //用户token中获取用户id
        $uid = $this->_uid;
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $lastid = $this->requestParam('lastid');
        $folder_uid = $this->requestParam('folder_uid',true);
        $ret=FavoriteFolderService::getUserFavFolderList($folder_uid,$rn,$lastid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
