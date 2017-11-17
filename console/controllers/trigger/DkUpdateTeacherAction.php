<?php

namespace console\controllers\trigger;

use Yii;
use api\lib\enumcommon\ReturnCodeEnum;
//use api\components\ApiBaseAction;
use yii\base\Action;
use common\models\myb\DkActivity;
use common\models\myb\DkCorrect;
#use common\service\DictdataService;
use common\service\dict\CointaskDictService;
use common\lib\myb\enumcommon\CointaskTypeEnum;
#use api\service\DkCorrectService;
use api\service\TweetService;
use api\service\UserCoinService;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\UserDetailService;
use api\service\CointaskService;

/**
 * 
 */
class DkUpdateTeacherAction extends Action {

    public function run() {


        #->createCommand()->getRawSql();
        //获取批改老师
        $activityData = DkActivity::find()->select('activityid,teacheruid,correct_count')->where(['<=', 'activity_etime', time()])->asArray()->all();

        if (!empty($activityData)) {
            foreach ($activityData as $key => $val) {
                $dkCorrect = DkCorrect::find()->select('dkcorrectid,activityid,f_catalog,f_catalog_id,content,source_pic_rid,s_catalog,s_catalog_id,submituid,teacheruid,zan_num,correctid')
                                ->where(['activityid' => $val['activityid']])
                                ->andWhere(['is', 'teacheruid', null])
                                ->andWhere(['is', 'correctid', null])
                                ->limit($val['correct_count'])
                                ->orderBy("zan_num desc")
                                ->asArray()->all();
                foreach ($dkCorrect as $k => $v) {
                    # echo '活动：' . $val['activityid'] . '===批改表：' . $v['dkcorrectid'] . '===用户id：' . $v['submituid'] . '===老师：' . $v['teacheruid'] . '<br/>';
                    $this->_activityDataInsert($val['teacheruid'], $v['source_pic_rid'], $v['content'], $v['f_catalog'], $v['f_catalog_id'], $v['s_catalog'], $v['s_catalog_id'], $v['submituid'], $v['dkcorrectid']);
                }
            }
            $this->_setForCorrect($activityData);
        } else {
            $data['message'] = '没有合适的活动';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
        }
    }

    /**
     * 批改老师已经达到饱和后随即分配老师给予批改
     * 作品对应分类下面的批改老师
     */
    private function _setForCorrect($activityData) {


        if (!empty($activityData)) {
            $connection = \Yii::$app->db;
            $sql = "select a.* from ci_user_detail as a inner join myb_user_correct as b on a.uid=b.uid where a.featureflag=1 and b.status=0";
            $command = $connection->createCommand($sql);
            $teacherData = $command->queryAll();

            $newActivityArray = [];
            foreach ($activityData as $k => $v) {
                $newActivityArray[] = $v['teacheruid'];
            }
            if (!empty($teacherData)) {
                $newTeacherArray = [];
                foreach ($teacherData as $key => $val) {
                    $newTeacherArray[] = $val['uid'];
                }
            }
            #去掉重复的老师
            $newActivityArray = array_unique($newActivityArray);
            #去掉已经分配过批改任务的老师
            $NewArray = array_merge(array_diff($newTeacherArray, $newActivityArray));
            foreach ($NewArray as $kkk => $vvv) {
                $sqlStr = "select uid,issketch,isdrawing,iscolor,isdesign from myb_user_correct where uid={$vvv}";
                $commandRes = $connection->createCommand($sqlStr);
                $dataTeacher[$kkk] = $commandRes->queryOne();
            }
            # print_r($dataTeacher);
            //素写
            $issketch = [];
            //素描
            $isdrawing = [];
            //色彩
            $iscolor = [];
            //设计
            $isdesign = [];


            //素写
            foreach ($dataTeacher as $kv => $vk) {
                if ($vk['issketch'] == 1) {
                    $issketch[5][] = $vk['uid'];
                }
            }

            //素描
            foreach ($dataTeacher as $kv => $vk) {
                if ($vk['isdrawing'] == 1) {
                    $issketch[4][] = $vk['uid'];
                }
            }

            //色彩
            foreach ($dataTeacher as $kv => $vk) {
                if ($vk['iscolor'] == 1) {
                    $issketch[1][] = $vk['uid'];
                }
            }

            //设计
            foreach ($dataTeacher as $kv => $vk) {
                if ($vk['isdesign'] == 1) {
                    $issketch[2][] = $vk['uid'];
                }
            }

            #  print_r($activityData);
            if (!empty($NewArray)) {
                foreach ($activityData as $key => $val) {
                    $dkCorrect = DkCorrect::find()->select('dkcorrectid,activityid,f_catalog,f_catalog_id,content,source_pic_rid,s_catalog,s_catalog_id,submituid,teacheruid,zan_num,correctid')
                                    ->where(['activityid' => $val['activityid']])
                                    ->andWhere(['is', 'teacheruid', null])
                                    ->andWhere(['is', 'correctid', null])
                                    ->orderBy("zan_num desc")
                                    ->asArray()->all();
                    # print_r($dkCorrect);

                    foreach ($dkCorrect as $k1 => $v1) {

                        if ($v1['f_catalog_id'] == 1) {
                            if (!$issketch[1])
                                continue;
                            $randArray = rand(0, count($issketch[1]) - 1);
                        } elseif ($v1['f_catalog_id'] == 2) {
                            if (!$issketch[2])
                                continue;
                            $randArray = rand(0, count($issketch[2]) - 1);
                        } elseif ($v1['f_catalog_id'] == 4) {
                            if (!$issketch[4])
                                continue;
                            $randArray = rand(0, count($issketch[4]) - 1);
                        } elseif ($v1['f_catalog_id'] == 5) {
                            if (!$issketch[5])
                                continue;
                            $randArray = rand(0, count($issketch[5]) - 1);
                        }
                        # 
                        $dkCorrect[$k1]['teacheruid'] = $issketch[$v1['f_catalog_id']][$randArray];
                    }
                    foreach ($dkCorrect as $values) {
                        #echo '活动：' . $values['activityid'] . '===批改表：' . $values['dkcorrectid'] . '===用户id：' . $values['submituid'] . '===老师：' . $values['teacheruid'] . '<br/>';
                        $this->_activityDataInsert($values['teacheruid'], $values['source_pic_rid'], $values['content'], $values['f_catalog'], $values['f_catalog_id'], $values['s_catalog'], $values['s_catalog_id'], $values['submituid'], $values['dkcorrectid']);
                    }
                }
            }
        }
    }

    /**
     * 改变数据库操作,求批改老师
     * @param type $teacheruid
     * @param type $picrid
     * @param type $content
     * @param type $fcatalog
     * @param type $fcatalogid
     * @param type $scatalog
     * @param type $scatalogid
     * @param type $uid
     * @param type $dkcorrectid 
     */
    private function _activityDataInsert($teacheruid, $picrid, $content, $fcatalog, $fcatalogid, $scatalog, $scatalogid, $uid, $dkcorrectid) {
//        //获取用户当天求批改数量
//        $num = TweetService::getCorrectCountToday($uid);
//        #if ($num >= 2) {
//        //获取用户当前金币数
//        $coinmodel = UserCoinService::getByUid($uid);
//        if ($coinmodel['remain_coin'] > 10) {
        try {
            //开启实物
            $innerTransaction = Yii::$app->db->beginTransaction();
            //(1)保存到批改表 myb_correct
            $model = new CorrectService();
            $model->submituid = $uid;
            $model->ctime = time();
            //批改当前状态 0未批改  1批改完成  2已撤销
            $model->status = 0;
            $model->content = $content;
            $model->teacheruid = $teacheruid;
            $model->source_pic_rid = $picrid;

            //(2)更改批改老师待批改数 myb_user_correct
            //myb_user_correct
            $user_correct = UserCorrectService::findOne(['uid' => $model->teacheruid]);
            $user_correct->queuenum = $user_correct->queuenum + 1;
            $user_correct->save();

            //(3)同步到广场
            //if($is_thread && $is_thread==1){
            # ci_tweet
            $tweetModel = new TweetService();
            $tweetModel['uid'] = $uid;
            // 获取数据 ci_user_detail
            $teachermodel = UserDetailService::getByUid($teacheruid);

            $tweetModel['title'] = '求' . $teachermodel['sname'] . '批改';
            $tweetModel['img'] = null;
            $tweetModel['content'] = $content;
            $tweetModel['tags'] = '';
            $tweetModel['type'] = 3; //3为求批改同步到广场的类型
            if ($fcatalog) {
                $tweetModel['f_catalog'] = $fcatalog;
                $tweetModel['f_catalog_id'] = $fcatalogid;
            } else {
                $tweetModel['f_catalog'] = '';
            }
            if ($scatalog) {
                $tweetModel['s_catalog'] = $scatalog;
                if ($tweetModel['f_catalog_id']) {
                    $tweetModel['s_catalog_id'] = $scatalogid;
                }
            } else {
                $tweetModel['s_catalog'] = '';
            }
            $tweetModel['resource_id'] = $picrid;
            $tweetModel['ctime'] = time();
            $tweetModel['utime'] = $tweetModel['ctime'];
            $tweetModel['correctid'] = $model->correctid;
            $tweetModel->save();
            $model->tid = $tweetModel->attributes['tid'];
            //v2.3.3批改增加主类型和子类型
            $model->f_catalog_id = $tweetModel->f_catalog_id;
            $model->s_catalog_id = $tweetModel->s_catalog_id;
            $model->save();
            $tweetModel['correctid'] = $model->correctid;
            $tweetModel->save();
            //(4)判断积分
//                $cointask = $this->coinTask($uid, $num + 1);
//                if ($cointask) {
//                    $data['cointask'] = $cointask;
//                }
            //推送小红点
            CorrectService::submitPushMsg($uid, $teacheruid, $model->correctid);
            //返回
            $data['correctid'] = $model->correctid;
            $data['tid'] = $model->tid;
            $dkcorrect = DkCorrect::findOne(['dkcorrectid' => $dkcorrectid]);
            $dkcorrect->correctid = $model->correctid;
            $dkcorrect->teacheruid = $teacheruid;
            $dkcorrect->save();
            $innerTransaction->commit();
            echo $dkcorrectid . '<br/>';
        } catch (Exception $ex) {
            $innerTransaction->rollBack();
            $data['message'] = '信息录入失败';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
        }
        #  }
        # }
    }

    /**
     * 处理金币
     * @param unknown $uid
     * @param unknown $num
     * @return boolean
     */
    private function coinTask($uid, $num) {
        //检查是否连续5天求批改
        if ($num == 1) {
            //检查上一次连续批改加金币时间间隔
            if (CointaskService::moreLastCorrectTaskTime($uid, 5)) {
                //判断是否已经连续5天提交
                if (TweetService::isContinueCorrect($uid, 5)) {
                    //加金币
                    $tasktype = CointaskTypeEnum::CONTINUE_CORRECT;
                    $coinCount = CointaskDictService::getCoinCount($tasktype);
                    UserCoinService::addCoinNew($uid, $coinCount);
                    //任务表加连续批改金币奖励记录
                    CointaskService::saveLastCorrect($uid);
                    return CointaskService::getReturnData($tasktype, $coinCount);
                }
            }
            return false;
        }
        //判断一天是否超过2个求批改    	
        if ($num > 2) {
            //扣金币	
            $tasktype = CointaskTypeEnum::MORE_CORRECT;
            $coinCount = CointaskDictService::getCoinCount($tasktype);
            //eci_user_coin 用户积分表
            UserCoinService::addCoinNew($uid, $coinCount);
            return CointaskService::getReturnData($tasktype, $coinCount);
        }
        return false;
    }

}
