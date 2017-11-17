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
class FolderInfoAction extends ApiBaseAction {
    
    public function run() {
        //用户token中获取用户id
        $uid = $this->_uid;
        $folderid = $this->requestParam('folderid', true);
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $lastid = $this->requestParam('lastid');
        if(!$lastid){
             //获取收藏信息
            $ret['folderinfo']=FavoriteFolderService::getFavFolderInfo($folderid);
            //用户信息
            $user_info=UserDetailService::getByUid($ret['folderinfo']['uid']);
            $user_info['follow_type']=UserRelationService::getBy2Uid($uid, $ret['folderinfo']['uid']);
            $ret['userinfo']=$user_info;
        }
        //获取画夹内容列表
        $ret['favlist']=FavoriteService::getFavFolderContentList($folderid,$uid,$rn,$lastid);
        //增加浏览数
        FavoriteFolderService::addHits($folderid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
