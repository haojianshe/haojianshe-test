<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use console\service\VideoResourceService;
use common\service\AliMtsService;

/**
 * 直播转录播后获取录播文件的时长和大小等信息
 */
class LiveMediainfoAction extends Action
{
    public function run()
    {
    	//(1)获取所有没有时长或大小的直播转录播的video资源
    	$models = VideoResourceService::getAllLiveWithoutMediainfo();
    	if(!$models || count($models)==0){
    		return ;
    	}
    	//(2)通过阿里云的mts 服务获取媒体文件信息
    	foreach ($models as $k=>$v){
    		//获取文件信息
    		$bucket = 'live-media-out';
    		$location = 'oss-cn-shanghai';
    		$object = $v['sourceurl'];
    		//object去掉url中不需要的部分,需要从myb_livemp4开始
    		$object = str_replace('http://','',$object);
    		$object = str_replace('https://','',$object);
    		$object = str_replace('live-media-out.oss-cn-shanghai.aliyuncs.com/','',$object);
    		$ret = AliMtsService::getMediaInfo($bucket, $location, $object);
    		if($ret==false){
    			continue;
    		}
    		//(3)更新video对象的文件信息，同时更新缓存文件
    		$videoModel = VideoResourceService::findOne(['videoid'=>$v['videoid']]);
    		$videoModel->video_length = floor($ret->MediaInfoJob->Properties->Format->Duration);
    		$videoModel->video_size = $ret->MediaInfoJob->Properties->Format->Size;
    		$videoModel->save();
    	}
    }    
}