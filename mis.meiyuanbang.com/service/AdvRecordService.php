<?php

namespace mis\service;

use Yii;
use common\models\myb\AdvRecord;
use common\models\myb\AdvRecordPlace;

use yii\data\Pagination;

use common\service\dict\AdvDictService;
use common\service\DictdataService;
use common\service\dict\CourseDictDataService;
use common\service\dict\LiveDictService;
use common\service\dict\CapacityModelDictDataService;


class AdvRecordService extends AdvRecord {
 	/**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($pos_type,$adv_f_catalog_id,$adv_s_catalog_id,$adv_t_catalog_id,$stime,$etime,$provinceid,$is_overtime) {
        $today = strtotime(date('Y-m-d', time()));

        $after5today=$today+5*24*60*60;
        $query = parent::find()->alias("a")->where(["a.status"=>1]);
        if($provinceid!=NULL){
            $query->innerJoin("myb_adv_record_place as d","a.advrecid=d.advrecid");
            $query->andWhere(['d.provinceid'=>$provinceid]);
        }
        if($is_overtime>0){
             $query->andWhere("( etime>$today and etime<=$after5today )");
        }
        if($pos_type>0){
            $query->andWhere(['pos_type'=>$pos_type]);
        }

        if($adv_f_catalog_id>0){
            $query->andWhere(['adv_f_catalog_id'=>$adv_f_catalog_id]);
        }

        if($adv_s_catalog_id>0){
            $query->andWhere(['adv_s_catalog_id'=>$adv_s_catalog_id]);
        }

        if($adv_t_catalog_id>0){
            $query->andWhere(['adv_t_catalog_id'=>$adv_t_catalog_id]);
        }
        if($stime>0){
            $query->andWhere([">=",'stime',$stime]);
        }
        if($etime>0){
            $query->andWhere(["<=",'etime',$etime]);
        }
        if($stime>0 && $etime>0){
            $query->andWhere("(($stime>=stime and etime>=$etime) or ($stime<=stime and etime<=$etime) or ($stime<=stime and stime <=$etime) or ($stime<=etime and etime <=$etime))");
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows_query = (new \yii\db\Query())
                ->select('a.*,b.*,c.*')
                ->from(parent::tableName()." as a")
                ->where(['a.status' => 1]);
        if($pos_type>0){
            $rows_query->andWhere(['pos_type'=>$pos_type]);
        }

        if($adv_f_catalog_id>0){
            $rows_query->andWhere(['adv_f_catalog_id'=>$adv_f_catalog_id]);
        }

        if($adv_s_catalog_id>0){
            $rows_query->andWhere(['adv_s_catalog_id'=>$adv_s_catalog_id]);
        }

        if($adv_t_catalog_id>0){
            $rows_query->andWhere(['adv_t_catalog_id'=>$adv_t_catalog_id]);
        }
        if($is_overtime>0){
             $rows_query->andWhere("( etime>$today and etime<=$after5today )");
        }
        if($stime>0){
            $rows_query->andWhere([">=",'stime',$stime]);
        }
        if($etime>0){
            $rows_query->andWhere(["<=",'etime',$etime]);
        }
        if($stime>0 && $etime>0){
            $rows_query->andWhere("(($stime>=stime and etime>=$etime) or ($stime<=stime and etime<=$etime) or ($stime<=stime and stime <=$etime) or ($stime<=etime and etime <=$etime))");
        }
        if($provinceid!=NULL){
            $rows_query->leftJoin("myb_adv_record_place as d","a.advrecid=d.advrecid");
            $rows_query->andWhere(['d.provinceid'=>$provinceid]);
        }
        $rows_query->leftJoin("myb_adv_resource as b","a.advid=b.advid")
                ->leftJoin("myb_adv_user as c","c.advuid=b.advuid")
                
                ->offset($pages->offset)
                ->limit($pages->limit);

       
                //->orderBy('sort_id DESC')
        $rows=$rows_query->all();
        foreach ($rows as $key => $value) {
            $rows[$key]['province']='';
            $province=AdvRecordPlace::find()->select("provinceid")->where(['advrecid'=>$value['advrecid']])->andWhere(['status'=>1])->asArray()->all();
            $province_names=[];
            if($province){
                foreach ($province as $key1 => $value1) {
                    if($value1['provinceid']>0){
                        $province_names[]=DictdataService::getUserProvinceById($value1['provinceid']);
                    }else{
                        $province_names[]='无地理位置';
                    }
                   
                }
                $rows[$key]['province']=implode(",",$province_names);
            }
            //echo json_encode($rows);exit;           
        }
        return ['models' => $rows, 'pages' => $pages];
    }
    /**
     * 根据分类id 获取分类信息
     * @param  [type] $ids [1-1-2]
     * @return [type]      [description]
     */
    public static function getCatalogById($ids){
        //返回数组
        $ret_arr=[];
        //参数处理
        $id_arr=explode("-", $ids);
        $pos_type=$id_arr[0];
        $adv_f_catalog_id=$id_arr[1];
        $adv_s_catalog_id=$id_arr[2];
        //得到所有分类
        $catalog=self::getCatalog();
        foreach ($catalog as $key => $value) {
                //banner
                if($pos_type==1 && $value['typeid']==$pos_type){
                    $ret_arr['typename']=$value['typename'];
                     foreach ($value['list'] as $key1 => $value1) {
                        if($value1['id']==$id_arr[1]){
                            //一级分类
                            $ret_arr['fcatlog']=$value1['name'];
                            //二级分类
                            $ret_arr['scatlog']=$value1['catalog'][$id_arr[2]];
                            $ret_arr['tcatlog']='';
                        }
                    }
                }else if($pos_type==2 && $value['typeid']==$pos_type){
                    //详情
                    $ret_arr['typename']=$value['typename'];
                    foreach ($value['details'] as $key1 => $value1) {
                        if($value1['id']==$id_arr[1]){
                            //一级分类
                            $ret_arr['fcatlog']=$value1['name'];
                            //二级分类
                            foreach ($value1['catalog'] as $key2 => $value2) {
                                if($value2['id']==$id_arr[2]){
                                    $ret_arr['scatlog']=$value2['name'];
                                    //三级分类
                                    $ret_arr['tcatlog']=$value2['scatalogs'][$id_arr[3]];
                                }
                            }
                        }
                    }
                }
        }
        return $ret_arr;
    }
    public static  function getCatalog(){
            $pos_type_arr=[];
            //1=>banner,2=>详情页
            $post_item['typeid']=1;
            $post_item['typename']='banner';
            //所有列表页
            $list=AdvDictService::getListAdvType();

            //能力模型取对应配置文件
            foreach ($list as $key => $value) {
                if($value['id']==1){
                    $list[$key]['catalog']=CapacityModelDictDataService::getCorrectMainType();
                }
            }
            $post_item['list']=$list;
            $pos_type_arr[]=$post_item;

            unset($post_item);

            $post_item['typeid']=2;
            $post_item['typename']='详情页';
            $details=[];
            //所有详情页
            $detail=AdvDictService::getDetailAdvType();
            foreach ($detail as $key => $value) {
                $detail_item['id']=$key;
                $detail_item['name']=$value;
                //分别获取对应详情配置
                switch (intval($key)) {
                    //素材 
                    case 1:
                        $catalogs=[];
                        $fcatalogs=DictdataService::getTweetMainType();
                        foreach ($fcatalogs as $key1 => $value1) {
                            $catalog_item['id']= $key1;
                            $catalog_item['name']= $value1;
                            $catalog_item['scatalogs']= DictdataService::getTweetSubType()[$key1];
                            $catalogs[]=$catalog_item;
                        }
                        $detail_item['catalog']=$catalogs;
                        break;
                    //视频  
                    case 2:
                        $catalogs=[];
                        $fcatalogs=CourseDictDataService::getCourseMainType();
                        foreach ($fcatalogs as $key1 => $value1) {
                            $catalog_item['id']= $key1;
                            $catalog_item['name']= $value1;
                            $catalog_item['scatalogs']= CourseDictDataService::getCourseSubType()[$key1];
                            $catalogs[]=$catalog_item;
                        }
                        $detail_item['catalog']=$catalogs;
                        break;
                    //批改 
                    case 3:
                        $catalogs=[];
                        $fcatalogs=DictdataService::getCorrectTypeAndTag()['data'];
                        foreach ($fcatalogs as $key1 => $value1) {
                            $catalog_item['id']= $fcatalogs[$key1]['subid'];
                            $catalog_item['name']= $fcatalogs[$key1]['name'];
                            $scatalogs=[];

                            foreach ($value1['catalog'] as $key => $value2) {
                               // var_dump($value2);exit;
                                $scatalogs_item[$value2['subid']]=$value2['name'];
                                
                            }
                            $catalog_item['scatalogs']=$scatalogs_item;
                            unset($scatalogs_item);
                            $catalogs[]=$catalog_item;
                        }
                        $detail_item['catalog']=$catalogs;
                        break;
                    default:
                        break;
                }
                $details[]=$detail_item;
                $detail_item=[];
            }
            $post_item['details']=$details;
            $pos_type_arr[]=$post_item;
            return $pos_type_arr;
    }
    public static function getAdvRecByAdvid($advid){
        return  self::find()->where(['advid'=>$advid])->andWhere(['status'=>1])->asArray()->all();
    }
}
