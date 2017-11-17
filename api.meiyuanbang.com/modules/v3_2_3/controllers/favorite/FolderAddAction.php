<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;

/**
 * 新建画夹
 */
class FolderAddAction extends ApiBaseAction {

    const STRING_LONG = '画夹名称不能大于20个字符';
    const FOLDER_EXTST = '画夹名称已存在';

    public function run() {
        //用户token中获取用户id
        $uid = $this->_uid;
        //画夹名称
        $name = $this->requestParam('name', true);
        $folderName = urldecode($name);
        
        
        //新建画夹不能大于20个字符
        if (mb_strlen($folderName) > 20) {
            $ret['errmsg'] = self::STRING_LONG;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $ret);
        }

        //如果存在则不能创建
        if (FavoriteFolderService::AddUserFavFolder($uid, $folderName) == false) {
            $ret['errmsg'] = self::FOLDER_EXTST;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $ret);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $favFolderData);
    }

}
