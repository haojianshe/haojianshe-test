<?php

namespace api\modules\v3_2_3\controllers\favorite;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\FavoriteFolderService;
use api\service\FavoriteService;
use api\service\UserDetailService;
use api\service\UserRelationService;

/**
 * 用户画夹列表
 */
class FolderListContentAction extends ApiBaseAction {
    
    public function run() {
    	//用户token中获取用户id
        $uid = $this->_uid;
        $tid = $this->requestParam('tid', true);
        $type = $this->requestParam('type', true);
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $lastid = $this->requestParam('lastid');
        //获取收藏过该用户的画夹id
        $foldeinfos=FavoriteFolderService::getContentFavFloderInfos($tid,$type,$rn,$lastid);
		//获取画夹内内容及用户信息
        if($foldeinfos){
            foreach ($foldeinfos as $key => $value) {
            	//获取画夹内容
                $fav=FavoriteService::getFavByfolderid($value['folderid'],7);
                //处理内容图片
                $foldeinfos[$key]['images']=FavoriteService::getFavContentInfo($fav);
                //获取用户信息
                $user_ext=UserDetailService::getUserExtInfo($value['uid']);
                $user_info=UserDetailService::getByUid($value['uid']);
                $user_info['follow_type']=UserRelationService::getBy2Uid($uid, $value['uid']);
                $foldeinfos[$key]['userinfo']=array_merge($user_ext,$user_info);
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $foldeinfos);
      
    }

}
