<?php
namespace api\controllers;
use api\components\ApiBaseController;

/**
* 小组相关功能
*/
class TeamController extends ApiBaseController
{	
	 public function behaviors()
    {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['get_members','add_admin_member','del_admin_member','get_team_info','join','out','del','team_edit','user_team_list'],
            ],
        ];
    }
	
	/**
	 *小组相关的action集合
	 */
	public function actions()
	{
		return [			
			//获取小组用户列表
			'get_members' => [
				'class' => 'api\controllers\team\GetMembersAction',
			],
			//添加管理员
			'add_admin_member' => [
				'class' => 'api\controllers\team\AddAdminMemberAction',
			],
			//取消管理员
			'del_admin_member' => [
				'class' => 'api\controllers\team\DelAdminMemberAction',
			],
			//小组信息
			'get_team_info' => [
				'class' => 'api\controllers\team\GetTeamInfoAction',
			],
			//搜索
			'members_search' => [
				'class' => 'api\controllers\team\MemberSearchAction',
			],
			//加入
			'join' => [
				'class' => 'api\controllers\team\JoinAction',
			],
			//退出小组
			'out' => [
				'class' => 'api\controllers\team\OutAction',
			],
			//退出小组
			'del' => [
				'class' => 'api\controllers\team\DelAction',
			],
			//小组信息编辑
			'team_edit' => [
				'class' => 'api\controllers\team\TeamEditAction',
			],
			//用户加入小组列表
			'user_team_list' => [
				'class' => 'api\controllers\team\UserTeamListAction',
			],
		];
	}
}