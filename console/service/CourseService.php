<?
namespace console\service;

use common\models\myb\Course;
use Yii;
use common\redis\Cache;
/**
*   
*/
class CourseService extends Course
{   
	/**
	 * 更改课程双十一价格
	 * @return [type] [description]
	 */
	public static function updatePrice_double11($price){
	    $redis = Yii::$app->cache;
	    $connection = Yii::$app->db;

	    $strsql = "update `myb_course` set `course_sale_price`=$price WHERE `status` =2 and `title` like '%状元笔记%'";
	    $command = $connection->createCommand($strsql);
	    $data = $command->execute();
	    //清除缓存，因为是一次性操作，所以直接清全部缓存
	    $redis->flushdb();
	}
}

