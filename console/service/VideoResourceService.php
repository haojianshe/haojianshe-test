<?php
namespace console\service;
use common\models\myb\VideoResource;
use Yii;
use common\redis\Cache;

/**
* 基础视频详情
*/
class VideoResourceService extends VideoResource
{
	/**
	 * 获取所有没有时长和大小的直播记录
	 */
	static function getAllLiveWithoutMediainfo(){
		$ret = (new \yii\db\Query())
		->select('*')
		->from(parent::tableName() . ' as a')
		->innerJoin('myb_live as b','a.videoid=b.videoid')
		->where("(a.video_size=0 or a.video_length=0)")
		->andWhere(['b.status'=>1]) //过滤删除的数据
		->all();
		return $ret;
	}
	
	/**
	 * 重写保存函数，清楚video的缓存
	 * @param string $runValidation
	 * @param string $attributeNames
	 * @return boolean
	 */
	public function save($runValidation = true, $attributeNames = NULL){
		$isnew = $this->isNewRecord;
		$redis = Yii::$app->cache;
		
		$ret = parent::save($runValidation,$attributeNames);
		if($isnew==false){
			$rediskey = "video_detail_".$this->videoid;
			$redis->delete($rediskey);
		}
		return $ret;		 
	}
}