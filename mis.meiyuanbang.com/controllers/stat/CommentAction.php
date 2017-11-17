<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\CommentService;
use mis\components\MBaseAction;
use common\service\PhpExcelService;
/**
 * 后台帖子列表页
 * 
 */
class CommentAction extends MBaseAction
{
    public $resource_id = 'operation_stat';

    public function run()
    {
       
        $request = Yii::$app->request;
        $search_con['start_time']=$request->get('start_time');
        if(empty($search_con['start_time'])){
            $search_con['start_time']=  date("Y-m-d 00:00:00",strtotime("-1 month"));  
        }


        $search_con['end_time']=$request->get('end_time');
         if(empty($search_con['end_time'])){
            $search_con['end_time']=date('Y-m-d 00:00:00',strtotime("+1 day"));
        }
        $search_con['user_type']=$request->get('user_type');
        $search_con['search_user']=$request->get('search_user');
        $search_con['font_count']=$request->get('font_count');
        if(empty($search_con['font_count'])){
           $search_con['font_count']=20;
        }
        $where='';
        if($search_con['user_type']==1){
             if($search_con['search_user']){
              $where =" and sname like '%".$search_con['search_user']."%'";
               // echo $where;
              }           
        }elseif($search_con['user_type']==2){
             if(isset($search_con['search_user']) && !empty($search_con['search_user'])){
                $where =" and uid in (".$search_con['search_user'].")";
            }
        }
        $where_time='';
        if($search_con['start_time']){
            $where_time =" and ctime>".strtotime($search_con['start_time']);
            $where_arr[]=array('>' ,'ctime', strtotime($search_con['start_time'])); 
        }
        if($search_con['end_time']){
            $where_time.=" and ctime<".strtotime($search_con['end_time']);
            $where_arr[]=array('<' , 'ctime',strtotime($search_con['end_time'])); 

        }

          //必须要有搜索条件否则返回
        $search_con['is_search']=$request->get('is_search');
        if($search_con['is_search']!=1){
            $search_con['is_search']=0;
            $data['search']=$search_con;
            return $this->controller->render('comment', $data);
        }
        //分页获取帖子列表
      $data = CommentService::getStatCommentByPage($where,$where_time,$search_con['font_count']);
      $data['search'] =$search_con;
      //PhpExcelService::output_stat_comment($data['models']);
      return $this->controller->render('comment', $data);

  }
}
