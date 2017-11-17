<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\PosidHome;

/**
 * 考点相关逻辑
 */
class PosidHomeService extends PosidHome
{ 
	//首页推荐位缓存
	private  $carousel_home_imgs = 'carousel_home_imgs';
	
	static function getMaxListorder(){
		$ret = parent::find()->where(['status'=>0])
		->max('listorder');
		return $ret;
	}
	
	/**
	 * 重载model的save方法，保存后处理缓存
	 * 因为更新频率很低，只要推荐有更新就清除掉首页相关的缓存
	 * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
	 */
	public function save($runValidation = true, $attributeNames = NULL){
		$redis = Yii::$app->cache;		
		$ret = parent::save($runValidation,$attributeNames);
		
		//处理缓存
		$rediskey = $this->carousel_home_imgs;
		$redis->delete($rediskey);
		//删除专题和改画广告缓存
		$redis->delete("postidhome_list".$this->channelid);
		return $ret;
	}
}
