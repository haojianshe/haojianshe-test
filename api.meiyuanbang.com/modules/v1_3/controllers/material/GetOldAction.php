<?php
namespace api\modules\v1_3\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取素材第二页以后的数据
 * @author Administrator
 *
 */
class GetOldAction extends ApiBaseAction
{
	public function run()
    {
    	//每页返回记录个数
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}    
    	//主类型
    	$mlevel = $this->requestParam('mlevel',true);
    	$lasttid = $this->requestParam('lasttid',true);
    	//分类型
    	$slevel = $this->requestParam('slevel');
    	if(!$slevel){
    		$slevel=0;
    		$tag = null;
    	}
    	else{
    		$tag = $this->requestParam('tag');
    		if($tag){
    			$tag= explode(',', $tag);
    		}
    	}
    	//(1获取ids (缓存)
    	$ret = TweetService::getMaterialIds($mlevel, $rn,$lasttid,$slevel,$tag);
    	//(2)获取帖子详情
    	$ret['content']=[];
    	if($ret['ids']){
    		foreach($ret['ids'] as $k=>$v){
    			//uid不填
    			$tweet = TweetService::fillExtInfo($v['tid'], -1);
    			//多图只返回第一图
    			$tweet['imgs'] = $tweet['imgs'][0];
    			if($tweet){
    				$ret['content'][]=$tweet;
    			}		
    		}
    	}
    	unset($ret['ids']);
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
