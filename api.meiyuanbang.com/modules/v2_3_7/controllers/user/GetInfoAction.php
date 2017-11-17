<?php
namespace api\modules\v2_3_7\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use api\service\UserCoinService;
use api\service\UserService;

use api\service\UserRelationService;
use api\service\TweetService;
use api\service\TeamInfoService;
use api\service\UserCorrectService;
use api\service\PublishingBookService;
use api\service\LiveService;
use api\service\CourseService;
use api\service\CorrectService;
use common\service\DictdataService;
/**
 * 获取用户信息 (H5端用 不需登陆)
 */

class GetInfoAction extends ApiBaseAction
{
    public function run()
    {   
    	$uid = $this->requestParam('uid',true); 
        $data=UserDetailService::getByUid($uid);
        //返回用户手机号
		$user_umobile=UserService::find()->select("umobile")->where(['id'=>$uid])->asArray()->one();
		$data=array_merge($data,$user_umobile);
       	$data['recent_news_num']=0;
		//积分信息
		$coins=UserCoinService::getByUid($uid);
		$data=array_merge($data,$coins);
		//粉丝关注数
		$data['follower_num']=UserRelationService::getFollowerNum($uid);
		$data['followee_num']=UserRelationService::getFolloweeNum($uid);
		//关注类型
		$data['follow_type']= UserRelationService::getBy2Uid($this->_uid, $uid);
		//帖子数
		$data['tweet_num']=TweetService::getTweetNum($uid);

		//小组人数
		$data['team_member_num']=0;
		$teaminfo=TeamInfoService::find()->where(['uid'=>$uid])->asArray()->one();
		if($teaminfo && ($data['ukind_verify']==1 || $data['featureflag']==1)){
			$data['team_member_num']=$teaminfo['membercount'];
		}
		//批改数
		$correct_userinfo=UserCorrectService::getUserCorrectDetail($uid);
		if($correct_userinfo && $data['featureflag']==1 ){
			$data=array_merge($data,$correct_userinfo);
		}
		//出版社图书数
		$data['book_num']=PublishingBookService::getBookCountByUid($uid);
		
		$data['share']['title']=$data['sname'];
        $data['share']['desc']=$data['intro'];
        $data['share']['img']=$data['avatar'];
        if($data['role_type']==3){
            $data['share']['url']=Yii::$app->params['sharehost']."/studio/drawing/index?studioid=".$data['uid'];
        }else{
            $data['share']['url']=Yii::$app->params['sharehost']."/user/user_detail?uid=".$data['uid'];
        }
        
		$data['course_num']=0;
		$data['live_num']=0;
		if(intval($data['ukind'])==1){
			$data['course_num']=CourseService::getUserCourseCount($uid);
			$data['live_num']=LiveService::getUserLiveCount($uid);
		}
		//隐藏邀请入口 1=>隐藏 其余数值显示
        $data['ishidden_invite']=1;
        $data['correct_count']=DictdataService::getCorrectTypeAndTag()['data'];
		foreach ($data['correct_count'] as $key => $value) {
			/*unset($data['correct_count'][$key]['catalog']);*/
			$data['correct_count'][$key]['count']=CorrectService::getUserAllCorrectCount($uid,$value['subid']);
		}
        //测试邀请入口
        /*
        $show_uids=[113429,13396,136755,136778,137062,28693,137104,22268];
        if(in_array($uid, $show_uids)){
            $user_info_res['ishidden_invite']=0;
        }*/
        
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
