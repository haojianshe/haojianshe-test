<?
namespace mis\service;

use common\models\myb\CapacityModel;
use Yii;
use common\redis\Cache;

/**
* 用户能力模型图
*/
class CapacityModelService extends CapacityModel
{  
	/**
	 * 删除用户某一个类型的能力模型
	 * @param unknown $uid
	 * @param number $catalogid 
	 */
	static function deleteUserCapacityModel($uid,$catalogid){
		$redis = Yii::$app->cache;
		$redis_key = 'userCapacityModel_' . $uid . '_' . $catalogid;
		
		//从数据库读取
		$model = parent::findOne(['uid'=>$uid,"catalogid"=>$catalogid]);
		if($model){
			$ret = $model->delete();
			//存缓存,保留24小时
			$redis->delete($redis_key);
		}
	}
}