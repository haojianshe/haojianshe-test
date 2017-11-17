<?php

namespace mis\service;

use Yii;
use common\models\myb\Correct;
use yii\data\Pagination;

class CorrectService extends Correct {

    /**
     * 获取老师反应时间对应的批改数
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCorrectCount($uid, $starttime, $endtime) {
        $teacher_corrects = self::find()->where(["<", "status", 2])->andWhere([">", 'ctime', $starttime])->andWhere(["<", 'ctime', $endtime])->andWhere(["teacheruid" => $uid])->all();
        $data['count'] = count($teacher_corrects); //总数
        $data['queuencount'] = 0; //待批改数
        $data['correctcount'] = 0; //已批改数
        $data['count5'] = 0;
        $data['count4'] = 0;
        $data['count3'] = 0;
        $data['count2'] = 0;
        $data['count_1'] = 0;
        $data['count_0'] = 0;
        $data['grade'] = 0;
        foreach ($teacher_corrects as $key => $value) {
            switch ($value['status']) {
                case 0:
                    $data['queuencount'] ++;
                    break;
                case 1:
                    $data['correctcount'] ++;
                    //判断是否老师加班时间
                    if ($value['ctime'] > strtotime(date("Y-m-d 08:30:00", $value['ctime'])) && $value['ctime'] < strtotime(date("Y-m-d 23:00:00", $value['ctime']))) {
                        //工作时间计算时间差
                        $time_diff = $value['correct_time'] - $value['ctime'];
                    } else {
                        //非工作时间计算时间差
                        if ($value['ctime'] >= strtotime(date("Y-m-d 23:00:00", $value['ctime']))) {
                            $time_diff = $value['correct_time'] - strtotime(date("Y-m-d 08:30:00", ($value['ctime'] + 24 * 60 * 60)));
                        } else {
                            $time_diff = $value['correct_time'] - strtotime(date("Y-m-d 08:30:00", $value['ctime']));
                        }
                    }
                    //根据时间差算每个时间段批改数
                    if ($time_diff < 30 * 60) {
                        $data['count5'] ++;
                    } else if ($time_diff >= 30 * 60 && $time_diff < 60 * 60) {
                        $data['count4'] ++;
                    } else if ($time_diff >= 60 * 60 && $time_diff < 3 * 60 * 60) {
                        $data['count3'] ++;
                    } else if ($time_diff >= 3 * 60 * 60 && $time_diff < 6 * 60 * 60) {
                        $data['count2'] ++;
                    } else if ($time_diff >= 6 * 60 * 60) {
                        $data['count_1'] ++;
                    }
                    break;
                default:
                    break;
            }
        }
        $data['grade'] = $data['count5'] * 5 + $data['count4'] * 4 + $data['count3'] * 3 + $data['count2'] * 2 + $data['count_1'] * -1;
        return $data;
    }

    /**
     * 获取用户分评，小于四十秒数
     * @param type $uid
     * @param type $starttime
     * @param type $endtime
     * @return int
     */
    public static function getCorrectCount($uid, $starttime, $endtime) {
        $query = new \yii\db\Query();
        $teacher_corrects = $query
                ->select('a.*,b.duration')
                ->from('myb_correct as a')
                ->leftJoin('myb_correct_talk as b', 'b.talkid=a.majorcmt_id')
                ->where(["a.status" => 1])
                ->andWhere([">", 'a.ctime', $starttime])
                ->andWhere(["<", 'a.ctime', $endtime])
                ->andWhere(["a.teacheruid" => $uid])
                ->all();
        #->createCommand()->getRawSql();//
        # echo $teacher_corrects;
        #exit;
        $data['count'] = count($teacher_corrects); //总数
        $data['correctcount'] = 0; //已批改数
        $data['notRedPenCount'] = 0; //无红笔
        $data['lessFortyCount'] = 0; //总评小于40秒
        $data['netCommentsCount'] = 0; //无分评
        $data['netPicCount'] = 0; //无范例图
        foreach ($teacher_corrects as $key => $value) {
            switch ($value['status']) {
                case 1:
                    $data['correctcount'] ++;
                    //总评小于40秒
                    if ($value['duration'] < 40) {
                        $data['lessFortyCount'] ++;
                    }
                    //无红笔
                    if (!$value['correct_pic_rid']) {
                        $data['notRedPenCount'] ++;
                    }
                    //无分评
                    if (!$value['pointcmt_ids']) {
                        $data['netCommentsCount'] ++;
                    }
                    //无范例图
                    if (!$value['example_pics']) {
                        $data['netPicCount'] ++;
                    }
                    break;
                default:
                    break;
            }
        }
        return $data;
    }

    /**
     * 获取用户分评，小于四十秒数
     * @param type $uid
     * @param type $starttime
     * @param type $endtime
     * @return int
     */
    public static function getCorrectList($uid, $starttime, $endtime) {
        $query = new \yii\db\Query();
        #红笔图
        $teacherRed = self::find()->where(["in", "status", 1])->andWhere([">", 'ctime', $starttime])->andWhere(["<", 'ctime', $endtime])->andWhere(["teacheruid" => $uid])->andWhere(['is', 'correct_pic_rid', null])
                        ->asArray()->all();

        #  print_r($teacherRed);
        #分评数
        $teacherPointcmt = self::find()->where(["in", "status", 1])->andWhere([">", 'ctime', $starttime])->andWhere(["<", 'ctime', $endtime])->andWhere(["teacheruid" => $uid])
                        ->andWhere(['is', 'pointcmt_ids', null])
                        # ->createCommand()->getRawSql();
                        ->asArray()->all();
        #  print_r($teacherPointcmt);
        #范例图
        $teacherExampl = self::find()->where(["in", "status", 1])->andWhere([">", 'ctime', $starttime])->andWhere(["<", 'ctime', $endtime])->andWhere(["teacheruid" => $uid])->andWhere(['is', 'example_pics', null])
                        ->asArray()->all();
        # print_r($teacherExampl);

        $teacherXiao = $query
                ->select('a.*,b.duration')
                ->from('myb_correct as a')
                ->leftJoin('myb_correct_talk as b', 'b.talkid=a.majorcmt_id')
                ->where(["a.status" => 1])
                ->andWhere([">", 'a.ctime', $starttime])
                ->andWhere(["<", 'a.ctime', $endtime])
                ->andWhere(["<", 'b.duration', 40])
                ->andWhere(["a.teacheruid" => $uid])
                ->all();
        # print_r($teacherXiao);
        $data['data'] = array_merge($teacherRed, $teacherPointcmt, $teacherExampl, $teacherXiao);
        foreach ($data['data'] as $kk => $vv) {
            $array[$kk] = $vv['correctid'];
        }

        foreach ($data['data'] as $v)
            $a[$v['correctid']] = $v;
        $t = array();
        if ($array) {
            foreach ($array as $k) {
                if (!in_array($a[$k]['correctid'], $t)) {
                    $res[] = $a[$k];
                    $t[] = $a[$k]['correctid'];
                } else {
                    for ($i = $k + 1; $i < count($a); $i++)
                        if (!in_array($a[$i]['correctid'], $t)) {
                            $res[] = $a[$i];
                            $t[] = $a[$i]['correctid'];
                            break;
                        }
                }
            }
            $data['data'] = $res;
        } else {
            $data['data'] = [];
        }


        return $data;
    }

    /**
     * 获取老师反应时间对应的批改数 old 
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    /* public static function getUserCorrectCount($uid,$starttime,$endtime){
      $connection = Yii::$app->db; //连接
      $table_name=parent::tableName();
      //查找
      $sql="select (select count(*) from $table_name where status<2 and  teacheruid=$uid and ctime between $starttime and  $endtime) as count,
      (select count(*) from $table_name where teacheruid=$uid and status=0 and ctime between $starttime and  $endtime) as queuencount,
      (select count(*) from $table_name where teacheruid=$uid and status=1 and ctime between $starttime and  $endtime) as correctcount,
      (select count(*) from $table_name where correct_time-ctime<30*60 and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count5,
      (select count(*) from $table_name where correct_time-ctime>=30*60 and correct_time-ctime<60*60 and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count4,
      (select count(*) from $table_name where correct_time-ctime>=60*60 and correct_time-ctime<3*60*60 and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count3,
      (select count(*) from $table_name where correct_time-ctime>=3*60*60 and correct_time-ctime<6*60*60 and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count2,
      (select count(*) from $table_name where correct_time-ctime>=6*60*60 and ctime>= unix_timestamp( DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m-%d 08:30:00')) and  ctime<= unix_timestamp(DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m-%d 23:00:00'))  and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count_1,
      (select count(*) from $table_name where correct_time-ctime>=6*60*60 and ( ctime>= unix_timestamp(DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m-%d 23:00:00'))  or ctime<= unix_timestamp( DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m-%d 08:30:00'))  )  and status<=1 and teacheruid=$uid  and ctime between $starttime and  $endtime) as count_0

      ";
      $command = $connection->createCommand($sql);
      $models = $command->queryAll();
      $models[0]['grade']=$models[0]['count5']*5+$models[0]['count4']*4+$models[0]['count3']*3+$models[0]['count2']*2+$models[0]['count_1']*-1;
      return  $models[0];
      }
     */

    /**
     * 得到老师对应时间总批改数
     */
    public static function getCorrectCountByTime($starttime, $endtime) {
        $connection = Yii::$app->db; //连接    
        $table_name = parent::tableName();
        //查找
        $sql = "select (select count(*) from $table_name where (status=0 or status=1)   and ctime between $starttime and  $endtime ) as totalcount,
                (select count(*) from $table_name where  status=0 and ctime between $starttime and  $endtime) as waitcount,
                (select count(*) from $table_name where status=1 and ctime between $starttime and  $endtime) as hadcount,
                (select count(*) from $table_name where status=2 and ctime between $starttime and  $endtime) as delcount
               ";
        $command = $connection->createCommand($sql);
        $models = $command->queryAll();
        return $models[0];
    }

    /**
     * 得到老师对应时间总批改数
     */
    public static function getAllUserSubmitCount($start_time, $end_time) {
        $query = new \yii\db\Query();
        $models = $query->select("*,count(*) as submitcount")
                ->from(parent::tableName())
                ->where(['status' => 0])
                ->orWhere(['status' => 1])
                ->andWhere(['>=', 'ctime', $start_time])
                ->andWhere(['<=', 'ctime', $end_time])
                ->groupBy('submituid')
                ->all();
        return $models;
    }

    /**
     * 学生老师批改关系统计
     * @param unknown $starttime
     * @param unknown $endtime
     * @return unknown
     */
    public static function getCorrectUserRelation($starttime = NULL, $endtime = NULL) {

        $querynum = parent::find();
        //select count(distinct(submituid)) from myb.myb_correct
        $querynum->where(['status' => '0'])->orWhere(['status' => '1']);
        if ($starttime) {
            $querynum->andWhere(['>', 'ctime', $starttime]);
        }
        if ($endtime) {
            $querynum->andWhere(['<', 'ctime', $endtime]);
        }
        $countnum = $querynum->count("distinct(submituid)");
        $pages = new Pagination(['totalCount' => $countnum, 'pageSize' => 25]);
        $query = new \Yii\db\Query();
        $query->select('submituid,teacheruid,count(*) as count')
                ->from(parent::tableName())
                ->where(['status' => '0'])->orWhere(['status' => '1']);
        if ($starttime) {
            $query->andWhere(['>', 'ctime', $starttime]);
        }
        if ($endtime) {
            $query->andWhere(['<', 'ctime', $endtime]);
        }
        $rows = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->groupBy('submituid,teacheruid')
                ->orderBy("count desc")
                ->all();
        //var_dump($rows);exit;
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取用户已经批改的次数 
     */
    public static function getUserCorrect($uid = 0) {
        $count = 0;
        if ($uid) {
            $count = self::find()->where(['submituid' => $uid])->andWhere(['status' => 1])->count();
        }
        return $count;
    }

}
