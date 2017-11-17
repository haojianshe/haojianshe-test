<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\components\MBaseAction;
use common\service\PhpExcelService;

/**
 * 后台帖子列表页
 * 
 */
class TweetAction extends MBaseAction
{
    public $resource_id = 'operation_stat';

	public function run()
    {
        $request = Yii::$app->request;
        //$sname=$request->get('sname');
        
        $search_con['start_time']='';
        $search_con['end_time']='';
        $search_con['order_by']='';
        $search_con['user_type']='';
        $search_con['search_user']='';

        $search_con['start_time']=$request->get('start_time');
        if(empty($search_con['start_time'])){
            $search_con['start_time']=  date("Y-m-d 00:00:00",strtotime("-1 month"));  
        }


        $search_con['end_time']=$request->get('end_time');
         if(empty($search_con['end_time'])){
            $search_con['end_time']=date('Y-m-d 00:00:00',strtotime("+1 day"));
        }
      
        $search_con['order_by']=$request->get('order_by');
        $search_con['user_type']=$request->get('user_type');
        $search_con['search_user']=$request->get('search_user');
        $where='';
        if (empty($search_con['order_by']) || !isset($search_con['order_by'])) {
            $search_con['order_by']='tweet_count';
            # code...
        }
        //echo '用户类型'.$search_con['user_type'].'排序'.$order_by.'结束时间'.strtotime($search_con['end_time']) .'开始时间'.strtotime($search_con['start_time']);
        
        if($search_con['user_type']==1){
             if($search_con['search_user']){
              $where =" and sname like '%".$search_con['search_user']."%'";
               
              }           
        }elseif($search_con['user_type']==2){
             if(isset($search_con['search_user']) && !empty($search_con['search_user'])){
                $where =" and uid in (".$search_con['search_user'].")";
            }
        }
        $where_time='';
        if($search_con['start_time']){
            $where_time =" and ctime>".strtotime($search_con['start_time']);
            //$where_arr[]=array('>' ,'ctime', strtotime($search_con['start_time'])); 
        }
        if($search_con['end_time']){
            $where_time.=" and ctime<".strtotime($search_con['end_time']);
            //$where_arr[]=array('<' , 'ctime',strtotime($search_con['end_time'])); 

        }

        //必须要有搜索条件否则返回
        $search_con['is_search']=$request->get('is_search');
        if($search_con['is_search']!=1){
            $search_con['is_search']=0;
            $data['search']=$search_con;
            return $this->controller->render('tweet', $data);
        }
        //分页获取帖子列表
        $data = TweetService::getTweetStatPage($where,$where_time ,$search_con['order_by']);
        $resource_ids='';
        foreach ($data['models'] as $key => $value) {
           // $where_arr['uid']=$value['uid'];
            // $where_arr['is_del']=0;
             $where_all=' uid='.$value['uid'].' and is_del=0 ';
             $where_all.=$where_time;
              $value=TweetService::getTweetStatByWhere($where_all);
            foreach ($value as $ke1y => $value1) {
              $resource_ids.=$value1['resource_id'].',';
            }
              $str=substr($resource_ids, 0,strlen($resource_ids)-1);
              $data['models'][$key]['img_count']=count(explode(',',$str));
              $resource_ids='';
        }
        $data['search']=$search_con;

      //PhpExcelService::output_stat_tweet($data['models']);
        return $this->controller->render('tweet', $data);

       }
}
