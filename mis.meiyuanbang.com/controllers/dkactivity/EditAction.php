<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkActivityService;
use mis\service\DkModulesService;
use mis\service\DkPushSmsService;
use mis\service\DkCorrectService;
use mis\service\UserService;

use common\service\SmsService;
use common\models\myb\UserCorrect;
use mis\service\CorrectTeacherFolderService;

/**
 * 编辑大咖改画
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_activity';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $activityid = $request->get('activityid');   
            if($activityid){
                //edit
                if(!is_numeric($activityid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = DkActivityService::findOne(['activityid' => $activityid]);
                $modules=DkModulesService::findAll(['activityid'=>$activityid]);
                $usersinfo=UserService::getInfoByUids($model->teacheruid);
                //短息群发
                $sms=DkPushSmsService::findOne(['sid'=>$activityid]);
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'usersinfo'=>$usersinfo,'sms'=>$sms]);
            }
            else{
                //add
                $model = new DkActivityService();
                //短信群发
                $sms=new DkPushSmsService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'usersinfo'=>[],'sms'=>$sms]);
            }
        }
        else{

            if($request->post('isedit')==1){
                //活动
                $model =  DkActivityService::findOne(['activityid' => $request->post('DkActivityService')['activityid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                //短信群发
                $sms=DkPushSmsService::findOne(['sid' => $request->post('DkPushSmsService')['sid']]);
                $sms->load($request->post());

            }else{
                //insert
                //活动
                $model = new DkActivityService();
                $model->load($request->post());
                $model->status = 3;
                //短信群发
                $sms=new DkPushSmsService();
                $sms->load($request->post());
                $sms->status = 1;
            }

            $model->activity_stime = strtotime($model->activity_stime);
            $model->activity_etime = strtotime($model->activity_etime);
            $model->reg_etime = strtotime($model->reg_etime);
            $model->live_stime = (int)(strtotime($model->live_stime));
            $model->live_etime = (int)(strtotime($model->live_etime)); 
            $model->ctime = time();
            if($model->save()){
                $activityteacher=UserCorrect::findOne(["uid"=>$model->attributes['teacheruid']]);
                if($activityteacher){
                    $activityteacher->isactivity=1;
                    $activityteacher->save();
                }else{
                    $activityteacher=new UserCorrect();
                    $activityteacher->uid=$model->attributes['teacheruid'];
                    $activityteacher->issketch=0;
                    $activityteacher->isdrawing=0;
                    $activityteacher->iscolor=0;
                    $activityteacher->gaincoin=0;
                    $activityteacher->queuenum=0;
                    $activityteacher->correctnum=0;
                    $activityteacher->isprivate=0;
                    $activityteacher->status=0;
                    $activityteacher->isactivity=1;
                    $activityteacher->save();
                }
                //新增大咖改画老师，初始化老师对应的常用范例图目录 否则客户端批改出错
                CorrectTeacherFolderService::initFolder($model->attributes['teacheruid']);
                $activityid=$model->attributes['activityid'];

                $sms->ptime = strtotime($sms->ptime);
                $sms->sid = $activityid;
                $sms->ctime = time();
                $sms->type = 1;
                if(empty($sms->content)){
                    $sms->content='';
                }
                if(empty($sms->ptime)){
                    $sms->ptime=time();
                }
                /*if($sms->status==2){
                        //获取用户发送短信息
                        $uids=DkCorrectService::getSubmitUids($activityid);
                        $mobiles_arr=UserService::getMobileByUids($uids);
                        $modules_str=implode(",",$mobiles_arr);
                        $return_obj=SmsService::SendMobileSms($modules_str,$sms->content,2,$sms->ptime);
                        //保存返回数据
                        if($return_obj->taskID){
                            $sms->taskid=json_encode($return_obj->taskID);
                        }
                        $sms->status=3;
                        $sms->message=json_encode($return_obj->message);
                        $sms->successcounts=$return_obj->successCounts;
                        $sms->returnstatus=json_encode($return_obj->returnstatus);
                        $sms->totalcounts=count($mobiles_arr);
                        $sms->mobiles=implode(",",$mobiles_arr);

                }*/
                $sms->save();
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'usersinfo'=>[],'sms'=>$sms]);
        }
    
    	
    }
    
}
