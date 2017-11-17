<?php
namespace mis\controllers\teacher;

use Yii;
use mis\service\UserService;
use mis\service\UserCorrectService;
use mis\components\MBaseAction;
use mis\service\TeamInfoService;
use mis\service\CorrectTeacherFolderService;

/**
 * 添加或者编辑红笔老师
 */
class RedEditAction extends MBaseAction
{
	public $resource_id = 'operation_teacher';
	
    public function run()
	{
    	$request = Yii::$app->request;
    	$isclose = false;
    	
    	if(!$request->isPost){
    		$uid = $request->get('userid');   
    		if(!is_numeric($uid)){
    			die('非法输入');
    		}
    		//根据userid获取用户信息
    		$usermodel = UserService::findOne(['uid' => $uid]);
    		if(!$usermodel){
    			die('非法输入');
    		}
    		//取得红笔老师数据
    		$redmodel = UserCorrectService::findOne(['uid' => $uid]);
    		if(!$redmodel){
    			$redmodel = new UserCorrectService();
    			$redmodel->correct_fee=0;
    		}
    		//取对应的小组信息
    		$teammodel = TeamInfoService::findOne(['uid'=>$uid]);
			if(!$teammodel){
				$teammodel = new TeamInfoService();
			}    		
    		return $this->controller->render('rededit', ['usermodel' => $usermodel,'redmodel'=>$redmodel,'teammodel'=>$teammodel]);
    	}
    	else{
    		$uid = $request->post('uid');
    		//密码用于更新小组，如果用户是非私密老师，则密码直接设置为''    		
    		if(isset($request->post('UserCorrectService')['isprivate'])){
    			//私密老师
    			$isprivate = 1;    			
    			$pwd = $request->post('pwd');
    		}
    		else{
    			//非私密老师
    			$pwd = '';
    			$isprivate = 0;
    		}
    		$usermodel = UserService::findOne(['uid' => $uid]);
    		if($request->post('isedit')==1){
    			//更新红笔老师表
    			$redmodel = UserCorrectService::findOne(['uid' => $uid]);
    			$redmodel->IsNewRecord = false;
    			$redmodel->load($request->post());
    			$redmodel->isprivate = $isprivate;
                if(array_key_exists("issketch",$request->post('UserCorrectService'))){
                    $redmodel->issketch=1;
                }else{
                    $redmodel->issketch=0;
                }

                if(array_key_exists("isdrawing",$request->post('UserCorrectService'))){
                    $redmodel->isdrawing=1;
                }else{
                    $redmodel->isdrawing=0;
                }
                 if(array_key_exists("iscolor",$request->post('UserCorrectService'))){
                    $redmodel->iscolor=1;
                }else{
                    $redmodel->iscolor=0;
                }
                if(array_key_exists("isdesign",$request->post('UserCorrectService'))){
                    $redmodel->isdesign=1;
                }else{
                    $redmodel->isdesign=0;
                }
               
    			$redmodel->status = 0;
    			$redmodel->save();
    			//判断是否需要更新小组
    			$teammodel = TeamInfoService::findOne(['uid'=>$uid]);
    			if($teammodel->password !=$pwd){
    				if($pwd == ''){
    					$teammodel->password = null;
    				}
    				else{
    					$teammodel->password = $pwd;
    				}
    				$teammodel->save();
    			}    			
    		}
    		else{
    			//新增红笔老师
    			//判断用户以前是否已经添加过小组信息
    			$teammodel = TeamInfoService::findOne(['uid'=>$uid]);
    			if(!$teammodel){
    				//第一次设置为殿堂老师需要添加小组初始数据
    				$teammodel = new TeamInfoService();
    				$teammodel->uid =$uid;
    				$teammodel->teamname = $usermodel->sname . '的小组';
    				$teammodel->membercount = 1;
    				$teammodel->ctime =time();
    			}
    			else{
    				$teammodel->IsNewRecord = false;
    			}
    			if($pwd!=''){
    				$teammodel->password = $pwd;
    			}    			
    			$teammodel->save();
    			//写红笔老师表,清除红笔老师缓存列表
    			$redmodel = new UserCorrectService();
    			$redmodel->load($request->post());
    			$redmodel->uid = $uid;
    			$redmodel->isprivate = $isprivate;
    			$redmodel->save();
    			//新增红笔老师后，初始化老师对应的常用范例图目录
    			CorrectTeacherFolderService::initFolder($uid);
    		}
    		//更新用户detail表
    		$usermodel->IsNewRecord = false;
    		$usermodel->featureflag = 1;
    		$usermodel->save();
    		//清除缓存
    		$this->removecache($uid,$teammodel->teamid);
    		return $this->controller->render('rededit', ['usermodel' => $usermodel,'redmodel'=>$redmodel,'teammodel'=>$teammodel,'msg'=>'成功','isclose'=>true]);
    	}
    }
    
    /**
     * 
     * @param unknown $uid
     */
    private function removecache($uid,$teamid){
    	//清除对应的小组缓存
    	TeamInfoService::remove_teaminfo_cache($teamid);
    	//清除对应的红笔老师
    	UserCorrectService::removecache($uid);
    	//清除对应的用户缓存
    	UserService::removecache($uid);
    }
}
