<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use mis\service\UserService;
use mis\components\MBaseAction;
class UserAction extends MBaseAction{
	public  function run(){
		$request = Yii::$app->request;
		$is_search=$request->get("is_search")?$request->get("is_search"):0;
		//开始结束时间
		$con['start_time']=$request->get("start_time");
		$con['end_time']=$request->get("end_time");
		//用户列表暂时不展示
		//$data=UserService::getrLoginUserByPage(strtotime($con['start_time']),strtotime($con['end_time']));
		/**
		 * 前端展示用户 暂时只展示总数 用于模板增加用户列表
			<tr class="tb_header">
			  <th width="20%">用户编号</th>  
			   <th width="20%">用户信息</th>
			  <th width="20%">类型</th>
			  <th width="20%">状态</th>
			  <th width="20%">时间</th>
			</tr>
			</tr>
			 </thead>
			  
			 <? foreach ($models as $model) { ?>
			 <tr class="tb_list">
			  <td><?= $model['id'] ?></td>
			  <td><img  style="height:50px;width:50px; margin-left:3px;margin-top:3px;" alt="无头像" src="<?if($model['avatar']){echo json_decode($model['avatar'])->img->n->url;} ?>"><?= $model['sname'] ?></td>
			  <td><?= $model['oauth_type'] ?></td>
			  <td><?= $model['register_status'] ?></td>
			  <td><?= date('Y-m-d',$model['create_time']); ?></td>
			</tr>          
			<?}?>
			<tr class="operate">
				      <td colspan="6">
						<div class="cuspages right">
						<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
						</div>      
				      </td>
			 </tr> **/
		
		//统计用户数
		if($con['start_time'] || $con['start_time']){
			$data['counts']=UserService::getAddUserCount(strtotime($con['start_time']),strtotime($con['end_time']));
		}else{
			$is_search=false;
			$counts['total']=0;
			$counts['weixin']=0;
			$counts['weibo']=0;
			$counts['qq']=0;
			$counts['mobile']=0;
			$counts['android']=0;
			$counts['ios']=0;
			$counts['other']=0;
			$data['counts']=$counts;
			
			$con['start_time']=date("Y-m-d 00:00:00",strtotime("-1 month"));
			$con['end_time']=date('Y-m-d 00:00:00',strtotime("+1 day"));
		}
		
		//返回搜索数据
		$data['con']=$con;
		//是否搜索
		$data['is_search']=$is_search;
		return $this->controller->render("user",$data);
	}
}