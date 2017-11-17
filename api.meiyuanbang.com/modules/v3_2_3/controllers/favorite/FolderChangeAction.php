<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;

/**
 * 新建画夹
 */
class FolderChangeAction extends ApiBaseAction {

    public function run() {
        //用户token中获取用户id
        $uid = $this->_uid;
        //要删除的收藏作品
        $fid = $this->requestParam('fid', true);
        //画夹id
        $folderid = $this->requestParam('folderid', true);
        
        //删除失败
        if (FavoriteFolderService::DelUserFavFolder($uid, $folderid,1, $fid) == false) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $favFolderData);
    }

}
