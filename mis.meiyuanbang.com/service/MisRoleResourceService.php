<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\MisRoleResource;
use common\service\DictdataService;
/**
 * 角色资源授权相关逻辑
 */
class MisRoleResourceService extends MisRoleResource
{    
    /**
     * 获取一个角色的所有授权资源
     */
    public static function getAllByRoleid($roleid){
    	$models = parent::find()->
    			where(['roleid' => $roleid])->all();
    	return $models;
    }
    
    /**
     * 获取多个角色的权限集合
     * @param unknown $roleids
     */
    public static function getAllByRoleids($roleids){
    	$models = parent::find()->
    	where(['roleid' => $roleids])->all();
    	return $models;
    }

     /**
     * 获取多个角色的权限集合缓存
     * @param unknown $roleids
     */
    public static function getAllByRoleidsRedis($roleids,$mis_userid){
        $redis = Yii::$app->cache;       
        $redis_key="misroleids".$mis_userid;
        $redis_resourceids=$redis->get($redis_key);
        if($redis_resourceids){
            return json_decode($redis_resourceids);
        }else{
            $resourceids=self::getAllByRoleids($roleids);
            foreach ($resourceids as $key => $value) {
                $roleids_arr[]=$value['resourceid'];
            }
            $redis->set($redis_key,json_encode($roleids_arr));
            //缓存1天
            $ret=$redis->expire($redis_key, 3600*24*1);
            if(!$ret){
                return array();
            }
            return $roleids_arr;
        }
    }


    /**
     * 判断顶部菜单是否显示
     * @param  [type] $mis_userid  [description]
     * @param  [type] $top_menu_id [description]
     * @return [type]              [description]
     */
    public static function showTopMenu($mis_userid,$top_menu_id){
        //菜单对应的用户角色id
        $top_menu_arr=DictdataService::getMisTopMenu();
        $model = Yii::$app->user->getIdentity();
        $roleids = explode(',', $model->roleids);
        //管理员有所有权限
        if (in_array(1, $roleids)){
                return true;
        }
        //根据用户角色判断是否显示顶部菜单
        foreach ($roleids as $key => $value) {
            if(in_array($value, $top_menu_arr[$top_menu_id])){
                return true;
            }
        }
        return false;      
    }

    /**
     * 判断左侧菜单是否显示
     * @param  [type] $mis_userid  [description]
     * @param  [type] $action_name [description]
     * @return [type]              [description]
     */
    public static function showLeftMenu($mis_userid,$action_name){
        $model = Yii::$app->user->getIdentity();
        $roleids = explode(',', $model->roleids);
        //管理员有所有权限
        if (in_array(1, $roleids)){
                return true;
        }
        //获取所有访问权限
        $roleresource = self::getAllByRoleidsRedis($roleids,$mis_userid);
        foreach ($roleresource as $k=>$v){
            if($v==$action_name){
                return true;
            }
        }
        return false;
    }

}
