<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\PublishingBookService;

/**
 * 获取图书信息
 */
class BookInfoAction extends ApiBaseAction
{
    public function run()
    {   
        $bookid = $this->requestParam('bookid',true); 
        $data=PublishingBookService::getPublishingBookInfo($bookid);
        //分享信息
        $share['title']=$data['title'];
        $share['desc']=$data['desc'];
        $share['img']=$data['img']->l->url;
        $share['url']=Yii::$app->params['sharehost']."/publishing/book_detail?bookid=".$data['bookid'];
        $data['share']=$share;
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
