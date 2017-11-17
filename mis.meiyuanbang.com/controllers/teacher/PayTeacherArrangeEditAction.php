<?php
namespace mis\controllers\teacher;
use Yii;
use mis\components\MBaseAction;
use mis\service\CorrectPayteacherArrangeService;
use mis\service\UserService;
/**
 * 编辑付费老师排班
 */
class PayTeacherArrangeEditAction extends MBaseAction {
    public $resource_id = 'operation_teacher';
    public function run() {
        $request = Yii::$app->request;
        $isclose = false;
        $msg = '';
       
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $arrangeid = $request->get('arrangeid');
            if ($arrangeid) {
                if (!is_numeric($arrangeid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = CorrectPayteacherArrangeService::findOne(['arrangeid' => $arrangeid]);
                return $this->controller->render('payteacherarrangeedit', ['model' => $model, 'msg' => $msg,  ]);
            } else {
                $model = new CorrectPayteacherArrangeService();
                return $this->controller->render('payteacherarrangeedit', ['model' => $model, 'msg' => $msg]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = CorrectPayteacherArrangeService::findOne(['arrangeid' => $request->post('CorrectPayteacherArrangeService')['arrangeid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new CorrectPayteacherArrangeService();
                $model->load($request->post());
                //$model->status = 3;
                //添加创建时间
                $model->ctime = time();
            }
            $model->btime = strtotime($model->btime);
            $model->etime = strtotime($model->etime);
            $wherearrangid='';
            if($model->arrangeid){
                $wherearrangid=" and arrangeid <> ".$model->arrangeid;
            }
            $fin=CorrectPayteacherArrangeService::find()->where("(($model->btime>=btime and $model->btime<=etime) or ($model->etime>=btime and $model->etime<=etime) or ($model->btime<=btime and $model->etime>=etime))".$wherearrangid)->one();
            if($fin){
                $isclose = false;
                $msg = '时间有重复！arrangeid:'.$fin->arrangeid;
                 return $this->controller->render('payteacherarrangeedit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
            }
            $teacheruids=$model->teacheruids;
            $uid_arr=explode(",", $teacheruids);
            foreach ($uid_arr as $key => $value) {
               $userinfo=UserService::getInfoByUids($value);
               if(!$userinfo){
                    $isclose = false;
                    $msg = '用户不存在 uid:'.$value;
                    return $this->controller->render('payteacherarrangeedit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
               }
            }
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
               $msg=json_encode($model->getErrors());
                //$msg = '保存失败';
            }
            return $this->controller->render('payteacherarrangeedit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }

}
