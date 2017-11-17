<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\TweetService;

/**
 * 邀请活动列表
 */
set_time_limit(0); //程序执行时间无限制
ini_set('memory_limit', '-1');

class TweetAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_activity';
    //mis中库的名称和表的名称
    private $old_data_name = 'myb';
    private $old_tab_name = 'ci_tweet';
    //新建的库名称和表的名称
    private $new_data_name = 'test';
    private $new_tab_name = 'ci_tweet';
    //存储拆分的标签表
    private $tag_separate = 'twee_tag_separate';
    //存储推荐表
    private $tweet_recommend = 'tweet_recommend';

    public function run() {

        $connection = Yii::$app->db; //连接
        #$query = "truncate table " . $this->new_data_name . "." . $this->tag_separate;
        # $command = $connection->createCommand($query);
        # $command->execute();
        # exit;
        //分页获取活动列表
        $sqls = "select tid,f_catalog_id,s_catalog_id,tags from " . $this->old_data_name . "." . $this->old_tab_name . " where tid>0 and type='1' and is_del=0 and tags is not null and tags <>'' and s_catalog_id is not null and f_catalog_id is not null and recommend_tid is null limit 10";
        $command_count = $connection->createCommand($sqls);
        $newData = $command_count->queryAll();
        
       #   print_r($newData);
        # exit;
        # 
//        $i = 1;
//        $str = " insert into " . $this->new_data_name . "." . $this->tag_separate . " (tid,f_catalog_id,s_catalog_id,tags,ctime) values ";
//        foreach ($newData as $key => $val) {
//            $newTag = explode(',', $val['tags']);
//            for ($x = 0; $x < count($newTag); $x++) {
//                $str .= '(' . $val['tid'] . ',' . $val['f_catalog_id'] . ',' . $val['s_catalog_id'] . ',\'' . $newTag[$x] . '\',\'' . time() . '\'),';
//                $i++;
//            }
//        }
//        $newstr = substr($str, 0, strlen($str) - 1) . ';';
//       # print_r($newstr);
//        # exit;
//       $commandSql = $connection->createCommand($newstr);
//        $commandSql->execute();
//        exit;
//        #插入到推荐表
        $queryStr = " insert into " . $this->new_data_name . "." . $this->tweet_recommend . " (tid,tids,ctime) values ";
        $arrayImplode = [];
//        #  print_r($newData);
        # ->createCommand()->getRawSql();/
        foreach ($newData as $key => $val) {
            $newTag = explode(',', $val['tags']);
            $rows[$val['tid']] = (new \yii\db\Query())
                            # , 'GROUP_CONCAT(sid)', 's_catalog_id'
                            ->select(['count(tid) as count', 'tid'])
                            ->from($this->new_data_name . "." . $this->tag_separate)
                            #->where([">", "sid", 0])
                            ->where(['f_catalog_id' => $val['f_catalog_id']])
                            ->andWhere(['s_catalog_id' => $val['s_catalog_id']])
                            ->andWhere(['in', 'tags', $newTag])
                            ->offset($pages->offset)
                            ->groupBy('tid')
                            ->orderBy('count desc,ctime desc')
                            ->limit(10)
                            ->createCommand()->getRawSql();
            #   ->all();
        }
        print_r($rows);
        exit;

        foreach ($rows as $k => $v) {
            foreach ($v as $kk => $vv) {
                $arrayImplode[$kk] = $vv['tid'];
            }
            $queryStr .= '(' . $k . ',\'' . implode(',', $arrayImplode) . '\',\'' . time() . '\'),';
        }
        $queryStrNew = substr($queryStr, 0, strlen($queryStr) - 1) . ';';
        $commandSql = $connection->createCommand($queryStrNew);
        $commandSql->execute();
    }

}
