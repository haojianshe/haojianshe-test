<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\TweetService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\CointaskService;
use api\service\UserCoinService;

/**
 * 请求批改
 */
class SubmitAction extends ApiBaseAction
{   
    public function run()
    {
        $request = Yii::$app->request;
        $restCount = 12; //老师休息阀值
        //检查参数
        $teacheruid=$this->requestParam('teacheruid',true);
        $picrid = $this->requestParam('source_pic_rid',true);
        $content = $this->requestParam('content',true);
        $fcatalog = $this->requestParam('f_catalog');
        $scatalog = $this->requestParam('s_catalog');
        //急速批改时老师不受繁忙限制
        $from = $this->requestParam('from');
        
        //主类型和分类型为汉字，先解码
        if($fcatalog){
        	$fcatalog = urldecode($fcatalog);
        } 
        if($scatalog){
        	$scatalog = urldecode($scatalog);
        }
        
        //老师禁止求批改
        $data['message']='';
        $user_info=UserCorrectService::getUserCorrectDetail($this->_uid);
        if(!empty($user_info)){
            //批改老师 不允许求批改
            if($user_info['status']<>'1'){
                $data['message']='错误操作...';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
            }           
        }
        //判断是老师是否设置了禁止批改
        $teacher_info=UserCorrectService::getUserCorrectDetail($teacheruid);
        if(intval($teacher_info['correct_fee'])>0){
            //得到当前时段的付费批改老师
            $teacher_now=UserCorrectService::getPayTeacherNow();
            //当前时段繁忙的批改老师
            $teacher_busy=UserCorrectService::getBusyTeacherNow();
            //当前可批改的付费老师
            $online_teacher=array_diff($teacher_now,$teacher_busy);
            if(!in_array($teacheruid, $online_teacher)){
                $data['message']='老师休息中';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
            }
        }else{
            //正常批改老师
            //急速批改的求批改不受繁忙限制
           if($from !='fast'){
                //老师状态0正常  1删除 2休息  3繁忙，休息和繁忙不接受批改
                if($teacher_info['status']>0){
                    $data['message']='老师休息中';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
                }
           } 
        }
        
        //3.0.2判断老师是否为删除
        if($teacher_info['status']==1){
        	$data['message']='老师暂不接受批改';
        	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }        
        $submit_f_catalog_id = DictdataService::getTweetMainTypeIdByName($fcatalog);
        $is_catalog_teacher=true;
        switch (intval($submit_f_catalog_id)) {
            case 5:
                //是否素写批改老师
                $is_catalog_teacher=(boolean)($teacher_info['issketch']==1);
                break;
            case 4:
                //是否素描批改老师
                $is_catalog_teacher=(boolean)($teacher_info['isdrawing']==1);
                break;
            case 1:
                //是否色彩批改老师
                $is_catalog_teacher=(boolean)($teacher_info['iscolor']==1);
                break;
            case 2:
                //是否设计批改老师
                $is_catalog_teacher=(boolean)($teacher_info['isdesign']==1);
                break;
            default:
                break;
        }
        if(!$is_catalog_teacher){
            $data['message']="你所选择老师不能批改你选择作品分类，请重新选择老师";
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        
        //检查金币
        $num = TweetService::getCorrectCountToday($this->_uid);
        if($num>=2){
        	//获取用户当前金币数
        	$coinmodel = UserCoinService::getByUid($this->_uid);
        	if($coinmodel['remain_coin']<10){
        		$data['message']='金币不足';
        		$data['coinless']=1;
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        	}
        }
        //(1)保存到批改表
        $model=new CorrectService();
        $model->submituid=$this->_uid;
        $model->ctime=time();
        //'批改当前状态 0未批改  1批改完成  2已撤销'
        $model->status=0;
        $model->content=$content;
        $model->teacheruid=$teacheruid;
        $model->source_pic_rid = $picrid;
        if($teacher_info['correct_fee']){
            $model->correct_fee = $teacher_info['correct_fee'];
        }
        
        //(2)更改批改老师待批改数
        $user_correct = UserCorrectService::findOne(['uid'=>$model->teacheruid]);
        $user_correct->queuenum = CorrectService::getWaitCorrectCount($model->teacheruid);
        if($user_correct->queuenum>=$restCount){
        	//老师待批该数超过阀值，改变状态为繁忙
        	$user_correct->status = 3;
        }
        $user_correct->save();
		//老师改为繁忙后处理缓存
		if($user_correct->status==3){
			UserCorrectService::changeRestCache($teacheruid);
		}        
        //(3)同步到广场
        //if($is_thread && $is_thread==1){
        	$tweetModel = new TweetService();
        	$tweetModel['uid'] = $this->_uid;
        	$teachermodel = UserDetailService::getByUid($teacheruid);
        	$tweetModel['title'] = '求'.$teachermodel['sname'].'批改';        	
        	$tweetModel['img'] =null;
        	$tweetModel['content'] = $content;
        	$tweetModel['tags'] = '';
        	$tweetModel['type'] = 3; //3为求批改同步到广场的类型
        	if($fcatalog){
        		$tweetModel['f_catalog'] = $fcatalog;
        		$tweetModel['f_catalog_id'] = DictdataService::getTweetMainTypeIdByName($fcatalog);
        	}
        	else{
        		$tweetModel['f_catalog'] = '';
        	}
        	if($scatalog){
        		$tweetModel['s_catalog'] = $scatalog;
        		if($tweetModel['f_catalog_id']){
        			$tweetModel['s_catalog_id'] = DictdataService::getTweetSubTypeIdByName($tweetModel['f_catalog_id'], $scatalog);
        		}
        	}
        	else{
        		$tweetModel['s_catalog'] ='';
        	}
        	$tweetModel['resource_id'] = $picrid;
        	$tweetModel['ctime'] = time();
        	$tweetModel['utime'] = $tweetModel['ctime'];
            $tweetModel['correctid'] = $model->correctid;
            $tweetModel->save();
            
            $model->tid=$tweetModel->attributes['tid'];
            //v2.3.3批改增加主类型和子类型
            $model->f_catalog_id=$tweetModel->f_catalog_id;
            $model->s_catalog_id=$tweetModel->s_catalog_id;
            $model->save();

            $tweetModel['correctid'] = $model->correctid;
            $tweetModel->save();
        //(4)判断积分
        $cointask = $this->coinTask($this->_uid, $num+1);
        if($cointask){
        	$data['cointask'] = $cointask;
        }
        //(5)免费批改推送小红点 付费在支付成功时
        if(intval($teacher_info['correct_fee'])==0){
            CorrectService::submitPushMsg($this->_uid,$teacheruid,$model->correctid);
        }
        //返回
        $data['correctid']= $model->correctid;
        $data['tid']= $model->tid;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    
    /**
     * 处理金币
     * @param unknown $uid
     * @param unknown $num
     * @return boolean
     */
    private function coinTask($uid,$num){
    	//检查是否连续5天求批改
    	if($num==1){
    		//检查上一次连续批改加金币时间间隔
    		if(CointaskService::moreLastCorrectTaskTime($uid,5)){
    			//判断是否已经连续5天提交
    			if(TweetService::isContinueCorrect($uid, 5)){
    				//加金币
    				$tasktype = CointaskTypeEnum::CONTINUE_CORRECT;
    				$coinCount = CointaskDictService::getCoinCount($tasktype);
    				UserCoinService::addCoinNew($uid, $coinCount);    				
    				//任务表加连续批改金币奖励记录
    				CointaskService::saveLastCorrect($uid);
    				return CointaskService::getReturnData($tasktype, $coinCount);
    			}	
    		}
    		return false;
    	}    	
    	//判断一天是否超过2个求批改    	
    	if($num>2){
    		//扣金币	
    		$tasktype = CointaskTypeEnum::MORE_CORRECT;
    		$coinCount = CointaskDictService::getCoinCount($tasktype);
    		UserCoinService::addCoinNew($uid, $coinCount);
    		return CointaskService::getReturnData($tasktype, $coinCount);
    	}
    	return false;   	
    }
}