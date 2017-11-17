<?php
namespace api\modules\v2_2_0\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\MaterialSubjectService;
use api\service\ResourceService;
use api\service\FavoriteService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取专题detail页数据
 */
class SubjectDetailAction extends ApiBaseAction
{
	public function run()
    {   
    	$sid = $this->requestParam('sid',true);
    	//(1)获取专题详情
    	$subjectinfo = MaterialSubjectService::getMaterialDetail($sid);
        MaterialSubjectService::addHits($sid);
        //获取收藏状态
        $subjectinfo['fav']=FavoriteService::getFavStatusByUidTid($this->_uid, $sid,1);
        //获取图片信息
    	$rids_arr=explode(",", $subjectinfo['rids']);        
        $subjectinfo['imgs_list']=[];
        foreach ($rids_arr as $key => $value) {
            $tmp=ResourceService::getResourceDetail($value);
            if($tmp){
                $subjectinfo['imgs_list'][]=$tmp;
            }            
        }
        //分享相关信息
        $subjectinfo['share']['title']=$subjectinfo["title"];
        $subjectinfo['share']['img']=json_decode($subjectinfo["picurl"])->n->url;
        $subjectinfo['share']['desc']=$subjectinfo["title"];
        $subjectinfo['share']['url']=Yii::$app->params['sharehost']."/material/index?subjectid=".$sid;
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,['content' =>$subjectinfo]);
    }
}
