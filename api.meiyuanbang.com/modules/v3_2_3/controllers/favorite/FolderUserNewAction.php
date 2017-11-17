<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;
use api\service\FavoriteService;

/**
 * 内容收藏的画夹列表
 */
class FolderUserNewAction extends ApiBaseAction {
    
    public function run() {
        //用户token中获取用户id
        $tid = $this->requestParam('tid', true);
        $type = $this->requestParam('type', true);
        $ret=FavoriteService::getFavInfoByContent($tid,$type);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
      
    }

}
