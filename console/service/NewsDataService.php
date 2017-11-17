<?php
namespace console\service;

use Yii;
use common\models\myb\NewsData;

class NewsDataService extends NewsData
{
	/**
	 * 更新newsid
	 * @param unknown $newsid
	 * @param unknown $oldnewsid
	 */
	static function update_newsid($newsid,$oldnewsid){
        $sql='UPDATE `myb_news_data` SET `newsid` = '.$newsid . ' WHERE `newsid` =  '.$oldnewsid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
    }
}
