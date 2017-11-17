<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Lecture;
use common\redis\Cache;
use common\service\DictdataService;

/**
 * 精讲相关逻辑
 * 0精讲 1课程 2活动
 */
class LectureService extends Lecture {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($f_catalog_id, $title, $s_catalog_id, $idname = '', $ztop = 0, $newstype = 0, $status = 0, $adminuser = 0, $start_time = 0, $end_time = 0) {
        $query = (new \yii\db\Query());
        $query->select(['b.*', 'a.*', 'c.supportcount', 'c.hits'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid');

        if ($status == 1) {#已审核
            $query->where(['a.status' => 0]);  //全部
        } elseif ($status == 2) {#未审核
            $query->where(['a.status' => 2]);  //全部
        } else {
            $query->where(['<>', 'a.status', 1]);  //全部
        }

//                ->andWhere(['<>','a.newstype',2]); //待审核
        if ($f_catalog_id) {
            $query->andWhere(['a.lecture_level1' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['a.lecture_level2' => $s_catalog_id]);
        }

        #发布时间 
        if ($start_time) {
            $query->andWhere(['>', 'a.publishtime',strtotime($start_time)]);
        }
        if ($end_time) {
            $query->andWhere(['<', 'a.publishtime', strtotime($end_time)]);
        }

        #发布人
        if ($adminuser) {
            $query->andWhere(['b.username' => $adminuser]);
        }
        #精讲id
        if ($idname) {
            $query->andWhere(['a.newsid' => $idname]);
        }
        #置顶
        if ($ztop == 1) {
            $query->andWhere(['a.stick_date' => 0]);
        } else if ($ztop == 2) {
            $query->andWhere(['>', 'a.stick_date', 0]);
        }
        #文章类型
        if ($newstype == 1) {
            $query->andWhere(['a.newstype' => 1]);
        } else if ($newstype == 2) {
            $query->andWhere(['a.newstype' => 2]);
        }

        if ($title) {
            $query->andWhere(['like', 'b.title', $title]);
        }
        $countQuery = $query->count();

        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery, 'pageSize' => 50]);

        //获取数据    	
        $rows = (new \yii\db\Query());

        $rows->select(['b.*', 'a.*', 'c.supportcount', 'c.hits'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid');
        if ($status == 1) {#已审核
            $rows->where(['a.status' => 0]);  //全部
        } elseif ($status == 2) {#未审核
            $rows->where(['a.status' => 2]);  //全部
        } else {
            $rows->where(['<>', 'a.status', 1]);  //全部
        }
//                ->andWhere(['<>','a.newstype',2]); //待审核
        if ($f_catalog_id) {
            $rows->andWhere(['a.lecture_level1' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows->andWhere(['a.lecture_level2' => $s_catalog_id]);
        }

        #发布人
        if ($adminuser) {
            $rows->andWhere(['b.username' => $adminuser]);
        }

        if ($start_time) {
            $rows->andWhere(['>', 'a.publishtime', strtotime($start_time)]);
        }
        if ($end_time) {
            $rows->andWhere(['<', 'a.publishtime', strtotime($end_time)]);
        }

        if ($idname) {
            $rows->andWhere(['a.newsid' => $idname]);
        }
        if ($ztop == 1) {
            $rows->andWhere(['a.stick_date' => 0]);
        } else if ($ztop == 2) {
            $rows->andWhere(['>', 'a.stick_date', 0]);
        }
        if ($newstype == 1) {
            $rows->andWhere(['a.newstype' => 1]);
        } else if ($newstype == 2) {
            $rows->andWhere(['a.newstype' => 2]);
        }
        if ($title) {
            $rows->andWhere(['like', 'b.title', $title]);
        }
        $data = $rows->offset($pages->offset)
                ->limit($pages->limit)
                //3.0.2版本改为按照发布时间排序
                ->orderBy('a.publishtime DESC')#->createCommand()->getRawSql();
                ->all();
        return ['models' => $data, 'pages' => $pages];
    }

    /**
     * 保存前处理缓存
     * 新增需要添加到list列表
     * 如果是删除时，则直接清列表，因为更新和删除频率非常低
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        //处理缓存,待审核状态时不需要操作缓存
        if ($this->status < 2) {
            //清空置顶缓存
            $redis->delete('top_lecture');
            //清空精讲详情缓存
            $redis->delete("lecture_detail_new_" . $this->newsid);
            //清空推荐列表缓存
            $redis->delete('all_lecture_list_0');
            //清空推荐外其他类型的列表缓存
            $lectureMaintypes = DictdataService::getLectureMainType();
            foreach ($lectureMaintypes as $k => $v) {
                $redis->delete('all_lecture_list_' . $v['maintypeid']);
            }
        }
        return $ret;
    }

    /**
     * 删除文章评论方法
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function udpate_cmtcount($subjectid) {
        $redis = Yii::$app->cache;
        $sql = "UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  " . $subjectid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        $redis_key = 'lecture_detail_new_' . $subjectid;
        $num = $redis->hincrby($redis_key, 'cmtcount', -1);
        if ($num < 0) {
            $redis->hset($redis_key, array('cmtcount' => 0));
        }
    }

    /**
     * 查询每一条专题下的tag
     * @param  [int]      $newid   [主题id]
     * @return [type]               [description]
     */
    public static function getAddtagPage($newid, $lecture_tagid) {
        $query = (new \yii\db\Query())
                ->select(['a.*', 'b.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_lecture_tag as b', 'a.newsid=b.newsid')
                ->where(['a.newstype' => 2])
                ->andWhere(['b.status' => 1])
                ->andWhere(['a.newsid' => $newid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.*', 'b.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_lecture_tag as b', 'a.newsid=b.newsid')
                ->where(['a.newstype' => 2])
                ->andWhere(['a.newsid' => $newid])
                ->andWhere(['b.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('b.listorder DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'newsid' => $newid, 'lecture_tagid' => $lecture_tagid];
    }

    /**
     * 查询每一条专题下的tag
     * @param  [int]      $newid   [主题id]
     * @return [type]               [description]
     */
    public static function getByNewsPage($lecture_tagid) {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from('myb_lecture_tag_news as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->where(['a.status' => 1])
                ->andWhere(['b.status' => 0])
                ->andWhere(['a.lecture_tagid' => $lecture_tagid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.tag_news_id', 'a.title', 'a.listorder', 'a.status', 'a.newsid', 'b.thumb'])
                ->from('myb_lecture_tag_news as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->where(['a.status' => 1])
                ->andWhere(['b.status' => 0])
                ->andWhere(['a.lecture_tagid' => $lecture_tagid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.listorder DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'lecture_tagid' => $lecture_tagid];
    }

}
