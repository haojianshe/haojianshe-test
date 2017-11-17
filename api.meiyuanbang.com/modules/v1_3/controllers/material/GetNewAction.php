<?php
namespace api\modules\v1_3\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\TweetService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 获取第一页素材
 * @author Administrator
 *
 */
class GetNewAction extends ApiBaseAction
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
    	$ret = TweetService::getMaterialIds($mlevel, $rn,0,$slevel,$tag);
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
    	//(3)如果分类型有值则添加对应标签信息
    	if($slevel){
    		$ret['tag_group']=DictdataService::getTweetSubTypeTags($slevel);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
