<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;
use api\service\CommentService;
/**
 * 问答详情
 */
class QaHasReplyCmtAction extends ApiBaseAction
{
    public function run()
    {   
        $newsid = $this->requestParam('newsid',true); 
        $last_cid = $this->requestParam('last_cid'); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'):10;
        $data['reply']=CommentService::getReplyQaCmt($newsid,"asc");
        $data['noreply']=[];
        if(!empty($data['reply'])){
        	foreach ($data['reply'] as $key => $value) {
	        	if(intval($value['ctype'])==2){
	        		if(!property_exists($value['voice'],'mp3url')){
	        			CommentService::AddVoiceToMp3Task($value['cid']);
	        		}
	        		
	        	}
	        }
        }
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
   
}

