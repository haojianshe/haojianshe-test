<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;
use common\models\myb\DkCorrect;
use common\models\myb\DkActivity;

use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 大咖改画求批改
 */
class SubmitAction extends ApiBaseAction {
    
    public function run() {
        $activityid = $this->requestParam('activityid', true);
        $f_catalog = $this->requestParam('f_catalog', true);
        $s_catalog = $this->requestParam('s_catalog', true);
        $content = $this->requestParam('content', true);
        $source_pic_rid = $this->requestParam('source_pic_rid', true);
        $uid=$this->_uid;

        $model=DkCorrect::findOne(['activityid'=>$activityid,'submituid'=>$uid]);
        if($model){
            $data['message']="此活动每个帐号只能提交一次";
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        
        $activity=DkActivity::findOne(['activityid'=>$activityid]);
        $limitcount=DkCorrect::find()->where(['activityid'=>$activityid])->count();
        
        if($limitcount >= $activity->max_count){
            $data['message']="超出活动人数上限！";
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $model= new DkCorrect();
        if($f_catalog){
            $model['f_catalog'] = $f_catalog;
            $model['f_catalog_id'] = DictdataService::getTweetMainTypeIdByName($f_catalog);
        }
        else{
            $model['f_catalog'] = '';
        }
        if($s_catalog){
            $model['s_catalog'] = $s_catalog;
            if($model['f_catalog_id']){
                $model['s_catalog_id'] = DictdataService::getTweetSubTypeIdByName($model['f_catalog_id'], $s_catalog);
            }
        }
        else{
            $model['s_catalog'] ='';
        }

        $model['activityid']=$activityid;
        $model['content']=$content;
        $model['ctime']=time();
        $model['source_pic_rid']=$source_pic_rid;
        $model['submituid']=$uid;
        //$model['teacheruid']=$activity->teacheruid;
        $model['zan_num']=0;
        $res=$model->save();
        if($res){
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }

    }

}
