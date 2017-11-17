<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;

use mis\service\AdvRecordService;
use common\service\DictdataService;
use common\models\myb\AdvRecordPlace;
use mis\service\AdvResourceService;

class RecordEditAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  private function getAdvInfo($advid){
    //根据id取出数据
    $model = AdvResourceService::find()->alias("a")->select("a.*,c.*")->where(['a.advid' => $advid])->innerJoin(AdvRecordService::tableName()." as c",'c.advid=a.advid')->andWhere(["c.status"=>1])->asArray()->one();

    //var_dump($model);exit;
    $find_advrec=AdvRecordService::find()->where(['advid'=>$advid])->andWhere(['status'=>1])->asArray()->all();
    $has_advarr=[];
    foreach ($find_advrec as $key => $value) {
        switch ($value['pos_type']) {
           // 1=>banner,2=>详情页
            case 1:
                $has_advarr[]=$value['pos_type'].'-'.$value['adv_f_catalog_id'].'-'.$value['adv_s_catalog_id'].'-'.$value['sortid'];
                break;
            case 2:
                $has_advarr[]=$value['pos_type'].'-'.$value['adv_f_catalog_id'].'-'.$value['adv_s_catalog_id'].'-'.$value['adv_t_catalog_id'];
                break;
            default:
                break;
        }
    }

    $provinceds=AdvRecordPlace::find()->where(['status'=>1])->andWhere(['advrecid'=>$model['advrecid']])->asArray()->all();

    $has_provinceids_arr=[];
    foreach ($provinceds as $key => $value) {
        $has_provinceids_arr[]=$value['provinceid'];
    }
    $data['has_provinceids_arr']=$has_provinceids_arr;
    $data['has_advarr']=$has_advarr;
    $data['model']=$model;
    return $data;

  }
  public function run()
    {        
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
      
        $todayTime= strtotime(date('Y-m-d 00:00:00'));
    	$catalog=AdvRecordService::getCatalog();

    	$province=DictdataService::getProvince();
        array_unshift($province,array('provinceid'=>0,'provincename'=>'无地理位置'));
        $advid = $request->get('advid'); 
        $advrecid = $request->get('advrecid'); 
        $has_advarr=[];
        $has_provinceids_arr=[];
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            if($advid){
                if(!is_numeric($advid)){
                    die('非法输入');
                }
                $data=$this->getAdvInfo($advid);
                $data['province']=$province;
                $data['msg']=$msg;
                $data['catalog']=$catalog;
                //echo json_encode($has_provinceids_arr);exit;
                return $this->controller->render('recordedit', $data);
            }
            else{
                $model = new AdvRecordService();
                return $this->controller->render('recordedit', ['model' => $model,'msg'=>$msg,'catalog'=>$catalog,'province'=>$province,'has_provinceids_arr'=>$has_provinceids_arr,'has_advarr'=>$has_advarr]);
            }
        }else{

            $post_data=$request->post()['AdvRecordService'];
            //var_dump($post_data['provice']);exit;
            //存储要投放的广告（包括banner和详情页）
            $add_data=[];
            //重复投放广告id存储
            $rep_advid=[];
            //banner位广告投放
            if(array_key_exists("list_catalog", $post_data)){
                foreach ($post_data['list_catalog'] as $key => $value) {
                    $catalogs=explode("-", $value);
                    $add_data_item['pos_type']=$catalogs[0];
                    $add_data_item['adv_f_catalog_id']=$catalogs[1];
                    $add_data_item['adv_s_catalog_id']=$catalogs[2];
                    $add_data_item['adv_t_catalog_id']='';
                    $add_data_item['advid']=$advid;
                    $add_data_item['sortid']=$post_data['sortid'][$value][0];
                    $add_data_item['stime']=strtotime($post_data['stime']);
                    $add_data_item['etime']=strtotime($post_data['etime']);
                    $add_data[]=$add_data_item;
                    $stime=$add_data_item['stime'];
                    $etime=$add_data_item['etime'];

                    //查找正在推广的列表广告id
                    $find_add_list=AdvRecordPlace::find()
                        ->alias("a")
                        ->select("b.advrecid,provinceid")
                        ->innerJoin(AdvRecordService::tableName().' as b','b.advrecid=a.advrecid')
                        ->where(['pos_type' => $add_data_item['pos_type']])
                        ->andWhere(['adv_f_catalog_id' => $add_data_item['adv_f_catalog_id']])
                        ->andWhere(['adv_s_catalog_id' => $add_data_item['adv_s_catalog_id']])
                        ->andWhere(['sortid' => $add_data_item['sortid']])
                        ->andWhere("(($stime>stime and etime>$etime) or ($stime<stime and etime<$etime) or ($stime<stime and stime <$etime) or ($stime<etime and etime <$etime))  and stime>".$todayTime."")
                        //->andWhere(['>=','etime',$add_data_item['stime']])
                        ->andWhere(['<>','b.advid',$advid])
                        ->andWhere(['a.status'=>1])
                        ->andWhere(['b.status'=>1])
                        ->andWhere(['in','a.provinceid',$post_data['provice']])
                        ->all();
                    if($find_add_list){
                        foreach ($find_add_list as $key => $value) {
                            $rep_advid_item['advrecid']=$value->advrecid;
                            $rep_advid_item['provinceid']=$value->provinceid;
                            $rep_advid[]=$rep_advid_item;
                        }
                    }
                }
            }
            unset($add_data_item);


            //查找正在推广的广告id
                /*$find_add_list=AdvRecordService::find()->filed("advid")->(['post_type' => $add_data_item['post_type'],'adv_f_catalog_id' => $add_data_item['adv_f_catalog_id'],'adv_s_catalog_id' => $add_data_item['adv_s_catalog_id'],'sortid' => $add_data_item['sortid']])->all();*/
            //详情页广告投放
            if(array_key_exists("detail_catalog", $post_data)){
                foreach ($post_data['detail_catalog'] as $key => $value) {
                    $catalogs=explode("-", $value);
                    $add_data_item['pos_type']=$catalogs[0];
                    $add_data_item['adv_f_catalog_id']=$catalogs[1];
                    $add_data_item['adv_s_catalog_id']=$catalogs[2];
                    $add_data_item['adv_t_catalog_id']=$catalogs[3];
                    $add_data_item['advid']=$advid;
                    $add_data_item['sortid']='';
                    $add_data_item['stime']=strtotime($post_data['stime']);
                    $add_data_item['etime']=strtotime($post_data['etime']);
                    $add_data[]=$add_data_item;
                    $stime=$add_data_item['stime'];
                    $etime=$add_data_item['etime'];

                    //查找正在推广的列表广告id
                    $find_add_list=AdvRecordPlace::find()
                        ->alias("a")
                        ->select("a.advrecid,provinceid")
                        ->innerJoin(AdvRecordService::tableName().' as b','b.advrecid=a.advrecid')
                        ->where(['pos_type' => $add_data_item['pos_type']])
                        ->andWhere(['adv_f_catalog_id' => $add_data_item['adv_f_catalog_id']])
                        ->andWhere(['adv_s_catalog_id' => $add_data_item['adv_s_catalog_id']])
                        ->andWhere(['adv_t_catalog_id' => $add_data_item['adv_t_catalog_id']])
                        ->andWhere("(($stime>stime and etime>$etime) or ($stime<stime and etime<$etime) or ($stime<stime and stime <$etime) or ($stime<etime and etime <$etime))  and stime>".$todayTime."")
                        ->andWhere(['<>','b.advid',$advid])
                        ->andWhere(['a.status'=>1])
                        ->andWhere(['b.status'=>1])
                        ->andWhere(['in','a.provinceid',$post_data['provice']])
                        ->all();
                    if($find_add_list){
                        foreach ($find_add_list as $key => $value) {
                            $rep_advid_item['advrecid']=$value->advrecid;
                            $rep_advid_item['provinceid']=$value->provinceid;
                            $rep_advid[]=$rep_advid_item;
                        }
                    }
                }
            }
            $re_msg='';
            foreach ($rep_advid as $key => $value) {
                $rep_detail=AdvRecordService::find()->alias('a')->select("a.*,b.*,c.*")->leftJoin(AdvResourceService::tableName().' as b',"a.advid=b.advid")->leftJoin('myb_adv_user as c',"c.advuid=b.advuid")->where(['a.advrecid'=>$value['advrecid']])->asArray()->one();
                $ids=$rep_detail['pos_type'].'-'.$rep_detail['adv_f_catalog_id'].'-'.$rep_detail['adv_s_catalog_id'].'-'.$rep_detail['adv_t_catalog_id'];
                $ret_catalogs=AdvRecordService::getCatalogById($ids);
                $re_msg.="分类：".$ret_catalogs['typename'].' '.$ret_catalogs['fcatlog'].' '.$ret_catalogs['scatlog'].' '.$ret_catalogs['tcatlog'].' '.'广告id：'.$rep_detail['advrecid'].' 广告主：'.$rep_detail['name'].' 标题：'.$rep_detail['title'].' 城市：'.DictdataService::getUserProvinceById($value['provinceid']).' 时间：'.date("Y.m.d",$rep_detail['stime']).'-'.date("Y.m.d",$rep_detail['etime']).'</br>';
            }
             
            //echo $re_msg;
            if($re_msg){
                $isclose = false;
                $re_msg='重复广告：</br>'.$re_msg;
                $msg =$re_msg;
                $data=$this->getAdvInfo($advid);
                $data['province']=$province;
                $data['msg']=$msg;
                $data['catalog']=$catalog;
                return $this->controller->render('recordedit',$data);
            }
           
             //echo json_encode ($rep_advid);exit;
            /*if($rep_advid){
                $msg='重复投放！';
                 $model = new AdvRecordService();
                return $this->controller->render('recordedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$catalog,'province'=>$province]);
            }*/


            if($request->post('isedit')==1){
                //根据id取出数据
                $model = AdvResourceService::find()->alias("a")->select("a.*,c.*")->where(['a.advid' => $advid])->leftJoin(AdvRecordService::tableName()." as c",'c.advid=a.advid')->asArray()->one();

                $all_rec=AdvRecordService::findAll(['advid'=>$advid]);
                AdvRecordService::updateAll(["status" => 2], ['advid'=>$advid]);
               
                
                $advrecid_s=[];
                foreach ($all_rec as $key => $value) {
                    $advrecid_s[]=$value->advrecid;
                }
                $all_rec_place=AdvRecordPlace::find()->where(['in','advrecid',$advrecid_s])->all();
                AdvRecordPlace::updateAll(["status" => 2], ['in','advrecid',$advrecid_s]);

                foreach ($add_data as $key => $value) {
                        $finquery=AdvRecordService::find();
                        $finquery->where(['advid'=>$advid]);
                        // 1=>banner,2=>详情页
                        switch ($value['pos_type']) {
                            case 1:
                                $finquery->andWhere(['adv_f_catalog_id'=>$value['adv_f_catalog_id']]);
                                $finquery->andWhere(['adv_s_catalog_id'=>$value['adv_s_catalog_id']]);
                                $finquery->andWhere(['sortid'=>$value['sortid']]);
                                break;
                            case 2:
                                $finquery->andWhere(['adv_f_catalog_id'=>$value['adv_f_catalog_id']]);
                                $finquery->andWhere(['adv_s_catalog_id'=>$value['adv_s_catalog_id']]);
                                $finquery->andWhere(['adv_t_catalog_id'=>$value['adv_t_catalog_id']]);
                                break;
                            default:
                                break;
                        }
                        $finmodel=$finquery->one();

                        $advrecid_s=0;
                        if($finmodel){
                            $finmodel->stime=$value['stime'];
                            $finmodel->etime=$value['etime'];
                            $finmodel->status=1;
                            $finmodel->save();
                            $advrecid_s=$finmodel->advrecid;
                        }else{
                            $model_new = new AdvRecordService();
                            $model_new->pos_type=$value['pos_type'];
                            $model_new->adv_f_catalog_id=$value['adv_f_catalog_id'];
                            $model_new->adv_s_catalog_id=$value['adv_s_catalog_id'];
                            $model_new->adv_t_catalog_id=$value['adv_t_catalog_id'];
                            $model_new->advid=$value['advid'];
                            $model_new->sortid=$value['sortid'];
                            $model_new->stime=$value['stime'];
                            $model_new->etime=$value['etime'];
                            $model_new->status = 1;
                            $model_new->ctime = time();
                            $ret=$model_new->save();
                            $advrecid_s=$model_new->attributes['advrecid'];
                        }


                        foreach ($post_data['provice'] as $key1 => $value1) {
                            $find_place=AdvRecordPlace::find()->where(['advrecid'=>$advrecid_s])->andWhere(['provinceid'=>$value1])->one();
                            if($find_place){
                                $find_place->status=1;
                                $find_place->save();
                            }else{
                                $place_new_model=new AdvRecordPlace();
                                $place_new_model->advrecid=$advrecid_s;
                                $place_new_model->provinceid=$value1;
                                $place_new_model->status=1;
                                $place_new_model->save();
                            }
                                
                        }

                }
                $isclose = true;
                $msg ='保存成功';
                

                //echo '编辑';exit;
                /* 
                    //编辑
                    $model =  AdvRecordService::findOne(['advrecid' => $request->post('AdvRecordService')['advrecid']]);
                    $model->IsNewRecord = false;
                    $model->load($request->post());
                */
            }else{
            	foreach ($add_data as $key => $value) {
            		$model = new AdvRecordService();
            		$model->pos_type=$value['pos_type'];
					$model->adv_f_catalog_id=$value['adv_f_catalog_id'];
					$model->adv_s_catalog_id=$value['adv_s_catalog_id'];
					$model->adv_t_catalog_id=$value['adv_t_catalog_id'];
					$model->advid=$value['advid'];
					$model->sortid=$value['sortid'];
					$model->stime=$value['stime'];
					$model->etime=$value['etime'];
					$model->status = 1;
					$model->ctime = time();
					$ret=$model->save();
					//var_dump($model->getErrors());exit;
					if($ret){
						foreach ($post_data['provice'] as $key => $value) {
							$placemodel=new AdvRecordPlace();
							$placemodel->advrecid=$model->attributes['advrecid'];
							$placemodel->provinceid=$value;
							$placemodel->status=1;
							$placemodel->save();
						}
					}
            	}
            	$isclose = true;
                $msg ='保存成功';
            	//echo json_encode($add_data);exit;
                /*//插入
                $model = new AdvRecordService();
                $model->load($request->post());
                $model->status = 2;
                $model->advid=$advid;
                //添加创建时间
                $model->ctime = time();*/
            }
           /* $model->etime = strtotime($model->etime);
            $model->stime = strtotime($model->stime);*/
            /*if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                var_dump($model->getErrors());
                $msg ='保存失败';
            }*/
            return $this->controller->render('recordedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$catalog,'province'=>$province,'has_provinceids_arr'=>$has_provinceids_arr,'has_advarr'=>$has_advarr]);
        }
    }
    
}
