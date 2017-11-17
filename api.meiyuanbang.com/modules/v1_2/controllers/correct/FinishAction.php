<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\TweetService;
use api\service\CapacityModelService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\CapacityModelDictDataService;
use api\service\CorrectTeacherFolderService;
use api\service\CorrectTeacherPicService;
use api\service\CorrectShareTaskService;
/**
 * 完成批改
 */
class FinishAction extends ApiBaseAction
{   
    public function run()
    {
        $correctid=$this->requestParam('correctid',true);
        $correct_pic_rid=$this->requestParam('correct_pic_rid');
        $majorcmt_id=$this->requestParam('majorcmt_id');
        //老师推荐课程，暂时确定为两个，[用逗号分开]
        $recommend_courseids=$this->requestParam('recommend_courseids');
        $score=$this->requestParam('score');
        $scorenum = $this->requestParam('scorenum');
        //获取批改实体和帖子实体
        $model =  CorrectService::findOne(['correctid' => $correctid]);
        $tweetModel= TweetService::findOne(['correctid'=>$correctid]);
    
        if($model->status==1){
        	//已批改则直接返回批改成功信息
        	$data['correctid']= $correctid;
        	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        if($this->_uid!=$model->teacheruid){
            die('用户无权限');
        }      
        
     

        $model->correct_time=time();
        $model->status=1;
        //批改完成图片id
        $model->correct_pic_rid=$correct_pic_rid;
        #老师推荐的课程ids
        $model->recommend_courseids=$recommend_courseids;
        //主语音id
        $model->majorcmt_id=$majorcmt_id;    
        //图片上的语音id 逗号隔开   
        $model->pointcmt_ids=$this->requestParam('pointcmt_ids');
        $model->example_pics=$this->requestParam('example_pics');
        $model->tid=$tweetModel->attributes['tid'];
        //处理打分
        if($score && $tweetModel->f_catalog_id){
        	//获取平均分
        	$teacherscore = json_decode($score,true);
        	if($teacherscore && count($teacherscore)>0){
	        	$itemlist = CapacityModelDictDataService::getCorrectScoreItemByMainId($tweetModel->f_catalog_id);
	        	//如果
	        	if($scorenum && is_numeric($scorenum)){
	        		$correctscore = $scorenum;
	        	}
	        	else{
	        		$correctscore = CapacityModelService::calScore($teacherscore, $itemlist);
	        	}	        	
	        	$model->score = $correctscore;
	        	$model->markdetail = $score;
        	}
        }
        if(!$model->save()){
        	//记录日志
        	Yii::error($model->attributes, __METHOD__);
        	//2.3.5增加提交失败处理
        	return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
        
        //更新用户的能力模型表
        if($score && $tweetModel->f_catalog_id){
        	$this->calCapacity($model->submituid, $tweetModel->f_catalog_id, $tweetModel->s_catalog_id, $model->ctime);
        }        
        //更改批改老师待批改数
        $user_correct=UserCorrectService::findOne(['uid'=>$model->teacheruid]);
        if($user_correct->queuenum>0){
            //减待批改数
            $user_correct->queuenum=$user_correct->queuenum-1;
        }else{
            $user_correct->queuenum=0;
        }
        //活动老师批改转mp3语音
        if($user_correct->isactivity==1){
            $sharemodel= CorrectShareTaskService::findOne(['correctid'=>$correctid]);
            if($sharemodel && $sharemodel->issuccess){
                //已进行过转化
                //$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
            }
            else{
                //确认correctid有效
                $correctmodel = CorrectService::getCorrectDetail($correctid);
                if(!$correctmodel || $correctmodel['status'] !=1){
                    //correctid无效 或者不是批改状态则返回
                    //$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
                }
                //保存任务
                if(!$sharemodel){
                    $sharemodel = new CorrectShareTaskService();
                    $sharemodel->correctid = $correctid;
                }
                $sharemodel->issuccess = 0;
                $sharemodel->ischange = 0;
                $sharemodel->sharetime = time();
                $sharemodel->save();
                //写cache
                CorrectService::shareTaskCache($correctid);
            }
        }
        //加批改作品数
        $user_correct->correctnum=$user_correct->correctnum+1;
        $user_correct->save();
        $data['correctid']= $model->attributes['correctid'];
        //更新列表里的原帖子
        $tweetModel->type = 4;
        $tweetModel->utime = time();
        $tweetModel->save(); 
        //处理老师常用的范例图
        if($model->example_pics){
        	$this->handleExamplePic($model->example_pics,$tweetModel->f_catalog,$model->f_catalog_id,$model->s_catalog_id);
        }
        //推送小红点
        CorrectService::finishPushMsg($this->_uid,$model->submituid,$data['correctid']);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    
    /**
     *  计算大类型下用户5次批改的能力模型
     * @param unknown $uid
     * @param unknown $catalogid
     * @param unknown $scatalogid
     */
    private function calCapacity($uid,$catalogid,$scatalogid,$lastSubmitTime){
    	//取出lastSubmitTime为截止时间的两周内最多5条数据
    	$stime = $lastSubmitTime-14*3600*24;
    	$marks = CorrectService::getUserLastCorrectMark($uid, $catalogid, $stime, $lastSubmitTime,5);
    	//更新
    	CapacityModelService::updateTotalScore($uid, $catalogid, $scatalogid, $marks);
    }
    
    /**
     * 老师新增范例图，或者更新范例图使用时间
     * @param unknown $rids
     * @param unknown $fcatalog
     */
    private function handleExamplePic($rids, $fcatalog, $f_catalog_id = '', $s_catalog_id = '') {
        $foldermodel = CorrectTeacherFolderService::getFolderByName($this->_uid, $fcatalog);
        if (!$foldermodel) {
            return;
        }
        $folderid = $foldermodel['folderid'];
        $arrrid = explode(',', $rids);
        $addcount = 0;
        $utime = time();
        foreach ($arrrid as $k => $v) {
            //判断是否添加过常用范例图
            if (CorrectTeacherPicService::addPic($this->_uid, $v, $folderid, $utime, $f_catalog_id, $s_catalog_id)) {
                $addcount += 1;
                $utime += 1;
            }
        }
        //增加范例图后，改变目录范例图数量
        if ($addcount > 0) {
            CorrectTeacherFolderService::updatePicCount($folderid, $addcount);
        }
    }
}
