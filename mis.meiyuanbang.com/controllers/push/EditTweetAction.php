<?php
namespace mis\controllers\push;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisXingePushService;
use common\service\XingeAppService;
/**
 * 帖子推送
 */
class EditTweetAction extends MBaseAction
{   
    public $resource_id = 'operation_push';
    public function run()
    { 
        $request = Yii::$app->request;
        $msg='';
        $isclose = false;
        
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $id = $request->get('id');     
            if($id){
                if(!is_numeric($id)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = MisXingePushService::findOne(['id' => $id]);
                return $this->controller->render('editt weet', ['model' => $model,'msg'=>$msg]);
            }
            else{
                $model = new MisXingePushService();
                return $this->controller->render('edittweet', ['model' => $model,'msg'=>$msg]);
            }
        }else{
            $push_person=$request->post('MisXingePushService')['push_person'];  //push_person =2 个人
            $state=0;//未发送状态
            $device_open_type=1;  //app 设备内跳转
            $device_open_detail='tweet'; //app跳转识别跳转
            $model = new MisXingePushService();
            $model->load($request->post());
            //参数赋值
            $model->id=$request->post('MisXingePushService')['id'];
            $model->title=$request->post('MisXingePushService')['title'];
            $model->content=$request->post('MisXingePushService')['content'];
            $model->push_person=$push_person;
            $model->send_time=strtotime($request->post('MisXingePushService')['send_time']);
            $model->create_time=time();
            $model->state=$state;
            $model->device_open_detail=$device_open_detail;
            $model->url_params='meiyuan://'.$device_open_detail.'?'.'tid='.urlencode($request->post('MisXingePushService')['url_params']);
            $model->device_open_type=$device_open_type;
            $model->push_device=$request->post('MisXingePushService')['push_device'];
            //群发只能大于当前时间
            if(strtotime($request->post('MisXingePushService')['send_time'])<time()  && $request->post('MisXingePushService')['push_person']==1){
                $msg ='要定时发送，不可选择当前之前的时间';
                $isclose = true;
                return $this->controller->render('editwap', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
            //发给个人 或者群发
            if($request->post('MisXingePushService')['push_person']==1){
                //群发
                $result=XingeAppService::push_device_by_type($model->push_device,$model->title,$model->content,$model->url_params, $request->post('MisXingePushService')['send_time']);
                $model->android_push_id=$result['android_push_id'];
                $model->ios_push_id=$result['ios_push_id'];
                $model->result=$result['result'];
            }else{
                //个人
                $model->device_token=$request->post('MisXingePushService')['device_token'];
                $result=XingeAppService::push_by_device_token($model->push_device,$model->title,$model->content,$model->device_token,$model->url_params,$request->post('MisXingePushService')['send_time']);
                $model->result=$result['result'];                    
            }                
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('edittweet', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}
