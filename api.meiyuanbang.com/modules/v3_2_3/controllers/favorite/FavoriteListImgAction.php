<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;
use api\service\FavoriteService;
use api\service\UserDetailService;
use api\service\UserRelationService;

/**
 * 用户最近的收藏列表图片
 */
class FavoriteListImgAction extends ApiBaseAction {
    
    public function run() {
    	//用户token中获取用户id
        $uid = $this->_uid;
        $fav_uid = $this->requestParam('fav_uid',true);
        //获取最近的收藏
        $favorite_infos=FavoriteService::getAllListByUid($fav_uid, 0, 7);
        $imgs=[];
        if($favorite_infos){
            $imgs=FavoriteService::getFavContentInfo($favorite_infos,$uid);
        }
        $data['images']=$imgs;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
      
    }

}
