<?
namespace mis\service;
use common\models\myb\CorrectPayteacherArrange;
use Yii;
use common\redis\Cache;
use yii\data\Pagination;

/**
* 付费老师排班
*/
class CorrectPayteacherArrangeService extends CorrectPayteacherArrange
{  
	public static function getByPage(){
		$query = parent::find();
       
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() )
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('arrangeid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
	}
}