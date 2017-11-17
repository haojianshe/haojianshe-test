<?php
namespace api\modules\v2_3_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectTeacherFolderService;
use api\service\CorrectTeacherPicService;
use api\service\UserDetailService;

class DelteacherpicAction extends ApiBaseAction{
    public  function run(){
    	//判断是否红笔老师
    	if(! UserDetailService::isCorrectTeacher($this->_uid)){
    		//返回非法用户
	    	$this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL);
    	}
    	//获取图片id列表和目录编号
    	$rids=$this->requestParam('rids',true);
    	$folderid = $this->requestParam('folderid',true);
    	
    	$addcount = 0;
    	$arrrid = explode(',', $rids);
    	foreach ($arrrid as $k=>$v){
    		//判断是否添加过这个素材
    		$model = CorrectTeacherPicService::findOne(['teacher_uid' => $this->_uid,'rid'=>$v]);
    		if($model){
    			$model->delete();
    			$addcount += 1;
    		}
    	}
    	//调整批改老师的常用范例图数量
    	$addcount = $addcount * -1;
    	if($addcount!=0){
    		CorrectTeacherFolderService::updatePicCount($folderid, $addcount);
    	}
    	$data['addcount'] = $addcount;
        //返回数据
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}