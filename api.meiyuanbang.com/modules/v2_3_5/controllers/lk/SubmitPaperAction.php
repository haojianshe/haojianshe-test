<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LkPaperService;
use api\service\LkPaperPicService;
use api\service\LkService;
/**
 * 提交模拟考
 */
class SubmitPaperAction extends ApiBaseAction
{
    public function run()
    {   
        //联考id
        $lkid = $this->requestParam('lkid',true); 
        //素描
        $imgs[1] = $this->requestParam('sumiao',true); 
        //色彩
        $imgs[2] = $this->requestParam('secai',true); 
        //速写
        $imgs[3] = $this->requestParam('suxie',true); 
        //内容
        $content = $this->requestParam('content'); 
        //姓名
        $user_name = $this->requestParam('user_name',true);
        //画室、学校 
        $studio_name = $this->requestParam('studio_name',true); 
        //身份id
        $professionid = $this->requestParam('professionid',true); 
        $uid=$this->_uid;
        $model=LkPaperService::findOne(["lkid"=>$lkid,"uid"=>$uid,"status"=>1]);
        if($model){
            $data['message']="已经提交过了！";
            return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $lkpagermodel=new LkPaperService();
        //  '联考活动id',
        $lkpagermodel->lkid=$lkid;
        //'创建时间',
        $lkpagermodel->ctime=time();
        //'所在画室',
        $lkpagermodel->studio_name=$studio_name;
        //'用户名称',
        $lkpagermodel->user_name=$user_name;
        //'内容',
        $lkpagermodel->content=$content;
        //'用户id',
        $lkpagermodel->uid=$uid;
        //'身份',  
        $lkpagermodel->professionid=$professionid;
        $lkpagermodel->total_score=0;
        $ret=$lkpagermodel->save();
        if($ret){
            //保存图片
            $paperid=$lkpagermodel->attributes['paperid'];
            foreach ($imgs as $key => $value) {
                $lkpagerpicmodel=new LkPaperPicService();
                $lkpagerpicmodel->paperid=$paperid;
                $lkpagerpicmodel->ctime=time();
                $lkpagerpicmodel->zp_type=$key;
                $lkpagerpicmodel->img_json=$value;
                $lkpagerpicmodel->score=0;
                $lkpagerpicmodel->save();
            }
            //更改参与人数
            $lk=LkService::findOne(['lkid'=>$lkid]);
            $lk->submit_count=$lk->submit_count+1;
            $lk->save();
            //清除缓存
            LkService::clearLkRedis($lkid);

            return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            return $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
    }
}
