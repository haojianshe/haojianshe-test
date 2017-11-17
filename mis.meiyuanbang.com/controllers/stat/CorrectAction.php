<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\CorrectService;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\CommentService;

/**
 * 后台帖子列表页
 * 
 */
class CorrectAction extends MBaseAction
{
  public $resource_id = 'operation_stat';

	public function run()
    {

        $request = Yii::$app->request;

        //用于返回页面筛选时间
        $search_con['start_time']=$request->get('start_time');
        if(empty($search_con['start_time'])){
            $search_con['start_time']=  date("Y-m-d 00:00:00",strtotime("-1 day"));  
        }

        $search_con['end_time']=$request->get('end_time');
        if(empty($search_con['end_time'])){
            $search_con['end_time']=date('Y-m-d 00:00:00');
        }
        
        
        //判断是否通过时间搜索  要是不通过时间搜索则显示空页
        if(!$request->get('is_search')){
            $search_con['is_search']=0;     
            return $this->controller->render('correct', ["is_search"=>1,"search"=>$search_con]);
        }
           
        $search_con['is_search']=1;        
        $start_time=strtotime($search_con['start_time']);
        $end_time=strtotime($search_con['end_time']);
        //得到所有批改老师列表
        $teachers=UserService::getAllCorrectTeacher();

        foreach ($teachers as $key => $value) {
            //增加获取的对应响应时间数量及分数
            $teachers[$key]=array_merge($teachers[$key], CorrectService::getUserCorrectCount($value['uid'],$start_time,$end_time));
            //增加批改老师评论数（帖子批改）
            $teachers[$key]['commentcount']=CommentService::getCorrectCmtCount($value['uid'],$start_time,$end_time);
            //用于排序
            $grades[]=$teachers[$key]['grade'];
        }

        //根据总分数排序数组
        array_multisort($grades, SORT_DESC, $teachers);
        //得到批改总数
        $total_info=CorrectService::getCorrectCountByTime($start_time,$end_time);
        //用户求批改数总数
        $subcountarr=CorrectService::getAllUserSubmitCount($start_time,$end_time);
        $subcount=array();
        $subcount['count1']=0;
        $subcount['count5']=0;
        $subcount['count10']=0;
        $subcount['count20']=0;
        $subcount['totalcount']=0;
         foreach ($subcountarr as $key => $value) {
             if($value['submitcount']>=1){
                    $subcount['count1']=$subcount['count1']+1;
             }
              if($value['submitcount']>=5){
                    $subcount['count5']=$subcount['count5']+1;
             }
              if($value['submitcount']>=10){
                    $subcount['count10']=$subcount['count10']+1;
             }
              if($value['submitcount']>=20){
                    $subcount['count20']=$subcount['count20']+1;
             }
         }
         $subcount['totalcount']=count($subcountarr);
        
        return $this->controller->render('correct', ["models"=>$teachers,"is_search"=>1,"search"=>$search_con,"total_info"=>$total_info,"subcount"=>$subcount]);

       }
}
