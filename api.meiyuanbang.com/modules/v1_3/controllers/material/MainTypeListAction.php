<?php
namespace api\modules\v1_3\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\TweetService;
use common\service\DictdataService;

/**
 * 获取帖子主类型信息
 */
class MainTypeListAction extends ApiBaseAction
{
	public function run()
    {
    	//获取主类型 分类型 tag信息
    	$tweettype = DictdataService::getTweetTypeAndTag()['data'];
    	//调整分类顺序
    	$tmp[] = $tweettype[3]; //素描
    	$tmp[] = $tweettype[4]; //素写
    	$tmp[] = $tweettype[0]; //色彩
    	$tmp[] = $tweettype[1]; //设计
    	$tmp[] = $tweettype[5]; //创作
    	$tmp[] = $tweettype[2]; //摄影
    	$ret['typelist'] = $tmp;
    	$ret['totalnum'] =  TweetService::getMaterialNum();
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
