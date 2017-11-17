<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\PublishingBookService;
use api\service\UserDetailService;
/**
 * 出版社图书推荐
 */
class PublishingBooksAction extends ApiBaseAction
{
    public function run()
    {   
        $uid = $this->requestParam('uid',true); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
       	$last_id=$this->requestParam('last_id') ? $this->requestParam('last_id'): NULL;
        $bookids=PublishingBookService::getPublishingBookList($uid,$last_id,$rn);
        $data['content']=PublishingBookService:: getPublishingBooksInfo($bookids);
        //分享信息
        $user_info=UserDetailService::getByUid($uid);
        $share['title']=$user_info['sname']."图书库";
        $share['desc']=$data['content'][0]['title']."等图书";
        $share['img']=$user_info['avatar'];
        $share['url']=Yii::$app->params['sharehost']."/publishing/publishing_books?uid=".$uid;
        $data['share']=$share;
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
