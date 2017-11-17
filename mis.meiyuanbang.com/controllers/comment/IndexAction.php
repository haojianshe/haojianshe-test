<?php
namespace mis\controllers\comment;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\CommentService;

/**
 * 后台评论列表页
 * 此action需要operation_cmt权限
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_cmt';
	
	public function run()
    {
        $request = Yii::$app->request;
        $sname=  trim($request->get('sname'));
        $subjecttype=$request->get('subjecttype');
        $subjectid=$request->get('subjectid');
        $where_arr=array();
        //姓名搜索条件
    	if(isset($sname) && !empty($sname)){
    		$where = "and cd.sname like '%".$sname."%'";
            $where_arr['sname']=$sname;
    	}else{
            $where_arr['sname']='';
    		$where='';
    	}
        //评论对象类型
        if(isset($subjecttype) && is_numeric($subjecttype)){
            $where .= " and subjecttype=$subjecttype";
            $where_arr['subjecttype']=$subjecttype;            
        }else{
            $where.='';
            $where_arr['subjecttype']='';
        } 
         //评论对象id
        if(isset($subjectid) && is_numeric($subjectid)){
            $where .= " and subjectid=$subjectid";
            $where_arr['subjectid']=$subjectid;            
        }else{
            $where.='';
            $where_arr['subjectid']='';
        }
        //创建时间默认赋值
        $where_arr['start_time']=$request->get('start_time');
        if(empty($where_arr['start_time'])){
            $where_arr['start_time']=  date("Y-m-d 00:00:00",strtotime("-7 day"));  
        }

        $where_arr['end_time']=$request->get('end_time');
         if(empty($where_arr['end_time'])){
            $where_arr['end_time']=date('Y-m-d 00:00:00',strtotime("+1 day"));
        }
        //取得时间搜索条件
        if($where_arr['start_time']){
            $where.=" and cc.ctime>".strtotime($where_arr['start_time']);
           
        }
        if($where_arr['end_time']){
            $where.=" and cc.ctime<".strtotime($where_arr['end_time']);
        }
        
        
        
    	//分页获取用户列表
    	$data = CommentService::getCommentByPage($where);
        //返回搜索条件
        $data['search_arr']=$where_arr;
    	return $this->controller->render('index', $data);
    }
}
