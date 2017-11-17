<?php

namespace api\service;

use Yii;
use common\models\myb\Studio;
use common\models\myb\StudioOpus;
use common\models\myb\StudioMenu;
use common\models\myb\Resource;
use common\models\myb\StudioSignuser;
use common\service\CommonFuncService;
use api\service\CourseService;
#use common\models\myb\StudioMenu;
use api\service\ResourceService;
#use common\service\CommonFuncService;
use common\models\myb\Orderinfo;

/**
 * 获取画室菜单信息
 *
 */
class StudioService extends Studio {

    /**
     * 获取画室菜单
     */
    public static function getStudioMenu($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_menu_list_' . $uid; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())->select('b.menuid,b.uid')
                    ->from('myb_studio_menu as b')
                    ->innerJoin('myb_studio as a', 'a.uid=b.uid')
                    ->where(["a.uid" => $uid])
                    ->andWhere(["b.menu_type" => 1])//审核通过
                    ->andWhere(["a.status" => 3])//审核通过
                    ->orderBy('b.listorder desc')
                    ->all();
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取班型缩略图
     */
    public static function getDetail($enrolid,$uid=-1) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_class_type_img_' . $enrolid; //缓存key
        $redis->delete($redis_key);
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())->select('classtype_img as live_thumb_url,enroll_title as title,a.classtypeid,a.uid')
                    ->from('myb_studio_enroll as b')->distinct()
                    ->innerJoin('myb_studio_classtype as a', 'a.classtypeid=b.classtypeid')
                    #->where(["b.status" => 1])//审核通过
                    ->where(["b.enrollid" => $enrolid])//审核通过
                    #->andWhere(["a.status" => 3])//审核通过
                    ->one();
            if ($data['classtypeid']) {
                $data['url'] = Yii::$app->params['sharehost'] . '/studio/drawing/entry?studioid=' . $data['uid'] . '&classtypeid=' . $data['classtypeid'];
                $mlist = json_encode($data);
                $redis->set($redis_key, $mlist);
                $redis->expire($redis_key, 3600);
            }
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取画室班型内容
     */
    public static function getStudio($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_menu_class_' . $uid; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data = (new \yii\db\Query())->select('b.classtype_title,b.uid,b.classtype_img,b.classtypeid')
                    ->from('myb_studio_classtype as b')
                    ->innerJoin('myb_studio as a', 'a.uid=b.uid')
                    ->where(["a.uid" => $uid])
                    ->andWhere(["b.status" => 3])//审核通过
                    ->andWhere(["a.status" => 3])//审核通过
                    ->orderBy('b.listorder desc')
                    ->all();
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取画室简介
     */
    public static function getStudioSynopsis($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_menu_synopsis_' . $uid; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $data['data'] = (new \yii\db\Query())->select('a.uid,b.tel,b.uid,b.addr_title,b.addr_detail,b.addr_img,b.addr_url')
                    ->from('myb_studio_address as b')
                    ->innerJoin('myb_studio as a', 'a.uid=b.uid')
                    ->where(["a.uid" => $uid])
                    ->andWhere(["b.status" => 1])//审核通过
                    ->andWhere(["a.status" => 3])//审核通过
                    ->orderBy('b.ctime asc')
                    ->all();
            $row = (new \yii\db\Query())->select('studio_desc')
                    ->from('myb_studio')
                    ->where(["uid" => $uid])
                    ->andWhere(["status" => 3])//审核通过
                    ->one();
            $array = array_merge($data, $row);
            $mlist = json_encode($array);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取画室作品展示
     */
    public static function getStudioOpus($uid, $lastid, $rn) {
        $redis = Yii::$app->cache;
        $rediskey = "myb_studio_opus_" . $uid;
        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getStudioOpusDb($uid);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['studioopusid'] . ',';
                $ret = $redis->rpush($rediskey, $value['studioopusid'], true);
            }
            $redis->expire($rediskey, 3600);
            $ids = substr($ids, 0, strlen($ids) - 1);
            $list_arr = explode(',', $ids);
        }
        //分页数据获取
        if (empty($lastid)) {
            $idx = 0;
            $ids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $list_arr);
            $ids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $ids_data;
    }

    /**
     * 根据用户获得产品展示
     * @param  [type] $uid [用户id]
     */
    public static function getStudioOpusDb($uid) {
        $ret = StudioOpus::find()->select("studioopusid")->where(['status' => 1])->andWhere(['uid' => $uid])->orderBy("studioopusid desc")->asArray()->all();
        if ($ret) {
            return $ret;
        } else {
            return [];
        }
    }

    /**
     * 根据用户作品id数组获取作品信息
     * @param  [type] $array [description]
     * @return [type]          [description]
     */
    public static function getStudioOpsusInfo($array) {
        $ret_arr = [];
        foreach ($array as $key => $value) {
            $ret_arr[] = self::getOpusInfo($value);
        }
        return $ret_arr;
    }

    /**
     * 获取详情
     */
    public static function getOpusInfo($opus) {
        $rediskey = "studio_opus_" . $opus;
        $redis = Yii::$app->cache;
        $detail = $redis->hgetall($rediskey);
        if (empty($detail)) {
            $detail = self::getStudioOpusInfoDb($opus);
            if ($detail) {
                $redis->hmset($rediskey, $detail);
                $redis->expire($rediskey, 3600);
            }
        }
        //处理图片
        if ($detail) {
            $detail['img'] = ResourceService::getResourceDetail($detail['resourceid'])['img'];
            $detail['img']->n = CommonFuncService::getPicByType((array) ($detail['img']->n), "n");
            $detail['img']->l = CommonFuncService::getPicByType((array) ($detail['img']->n), 'l');
        }
        return $detail;
    }

    /**
     * 数据库获取作品详情
     * @param  [type] $bookid [description]
     * @return [type]         [description]
     */
    public static function getStudioOpusInfoDb($studioopusid) {
        return StudioOpus::find()->where(['studioopusid' => $studioopusid])->andWhere(['status' => 1])->asArray()->one();
    }

    /**
     * 视频课程
     * @param  [type] $bookid [description]
     * @return [type]         [description]
     */
    public static function getStudioCourseInfo($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_studio_course'; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            $row = (new \yii\db\Query())->select('uid')
                    ->from('myb_studio_teacher')
                    ->where(["uuid" => $uid])
                    ->all();
            $array = [];
            foreach ($row as $key => $val) {
                $array[$key] = $val['uid'];
            }


            #四个老师的直播课
            $teacheCourse = (new \yii\db\Query())->select('courseid,teacheruid,title,hits_basic,hits,thumb_url')
                    ->from('myb_course')
                    ->where(["teacheruid" => $array])
                    ->andWhere(["status" => 2])
                    ->limit(4)
                    ->orderBy('ctime desc')
                    ->all();
            foreach ($teacheCourse as $k => $v) {
                $teacheCourse[$k]['price'] = CourseService::getCourseVideoPrice($v['courseid']);
                $teacheCourse[$k]['url'] = Yii::$app->params['sharehost'] . '/course/detail?courseid=' . $v['courseid'];
            }
            #2个直播课程
            $teacherArray = (new \yii\db\Query())->select('liveid,teacheruid,live_title,hits_basic,hits,recording_thumb_url')
                    ->from('myb_live')
                    ->where(["teacheruid" => $array])
                    ->andWhere(["status" => 1])
                    ->limit(2)
                    ->orderBy('start_time desc')
                    ->all();
            #print_r($array);
            #print_r($teacherArray);
            # exit;
            if ($teacherArray) {
                //预告页面 做进一步调整
                $url = "/video/live/live_trailer?liveid=";
                foreach ($teacherArray as $key => $val) {
                    $teacherArray[$key]['url'] = Yii::$app->params['sharehost'] . $url . $val['liveid'];
                }
            }
            $rows['teacherArray'] = $teacherArray;
            $rows['teacheCourse'] = $teacheCourse;
            $mlist = json_encode($rows);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取画室老师
     */
    public static function getStudioTeacherInfo($uid, $menutype) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_teacher_list_' . $uid . '_menutype_' . $menutype; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            $ret = StudioMenu::find()->select("studiomenuid")->where(['menuid' => $menutype])->andWhere(['uid' => $uid])->asArray()->one();
            //数据库获取
            $data = (new \yii\db\Query())->select('b.articleid,b.cover_type,b.article_type,a.title,a.thumb,c.copyfrom,a.desc,b.listorder,b.newsid,b.studiomenuid,c.content,c.hits,c.cmtcount,c.supportcount,c.copyfrom')
                    ->from('myb_studio_article as b')
                    ->innerJoin('myb_news as a', 'a.newsid=b.newsid')
                    ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid')
                    ->where(["b.studiomenuid" => $ret['studiomenuid']])
                    ->andWhere(["a.catid" => 7])//审核通过
                    ->andWhere(["a.status" => 0])
                    ->andWhere(["b.status" => 1])
                    ->orderBy('b.listorder desc')
                    ->all();
            if ($data) {
                foreach ($data as $key => $val) {
                    if ($val['cover_type'] == 2 || $val['cover_type'] == 3 || $val['cover_type'] == 5) {
                        $data[$key]['imgs'] = Resource::find()->select('img')->where(['rid' => $val['thumb']])->asArray()->one();
                    } else if ($val['cover_type'] == 4) {
                        $data[$key]['imgs'] = Resource::find()->select('img')->where(['in', 'rid', explode(',', $val['thumb'])])->asArray()->all();
                    } else if ($val['cover_type'] == 1 || $val['cover_type'] == 6) {
                        $data[$key]['imgs'] = 0;
                    }
                }
            }
            $mlist = json_encode($data);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 获取画室班型以及班型下面的报名方式
     */
    public static function getStudioClassType($uid, $classtypeid) {
        $redis = Yii::$app->cache;
        $redis_key = 'studio_class_type_' . $classtypeid . '_' . $uid; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            //数据库获取
            #b.classtypeid,b.classtype_title,b.classtype_img,b.class_desc,b.tel,b.classtype_sum,b.classtype_sum,
            $data['classType'] = (new \yii\db\Query())->select('a.enrollid,a.enroll_title,a.original_price,a.discount_price,a.enroll_desc')
                    ->from('myb_studio_enroll as a')
                    ->where(["a.classtypeid" => $classtypeid])
                    ->andWhere(["a.uid" => $uid])
                    ->andWhere(["a.status" => 1])//正常
                    ->orderBy('a.listorder asc')#->createCommand()->getRawSql();
                    ->all();
            $row['class'] = (new \yii\db\Query())->select('classtypeid,classtype_title,classtype_img,class_desc,tel,classtype_sum,classtype_sum,classtype_consultant,classtype_content')
                    ->from('myb_studio_classtype as a')
                    #->innerJoin('myb_studio as b', 'a.uid=b.uid')
                    ->where(["a.uid" => $uid])
                    ->andWhere(["a.status" => 3])//审核通过
                    # ->andWhere(["b.status" => 3])//审核通过
                    ->andWhere(["classtypeid" => $classtypeid])#->createCommand()->getRawSql();//审核通过
                    ->one();
            $array = array_merge($data, $row);
            $mlist = json_encode($array);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 600);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

    /**
     * 
     * @param type $uid   用户id
     * @param type $classtypeid 班型id
     * @param type $enrollid   报名方式id
     * @param type $name   姓名
     * @param type $mobile 电话
     * @param type $QQ   QQ
     * @param type $school 学校
     */
    public static function SetEnroll($uid, $classtypeid, $enrollid, $name, $mobile, $QQ, $school, $time) {
        $studioSignuser = new StudioSignuser();
        $studioSignuser->uid = $uid;
        $studioSignuser->classtypeid = $classtypeid;
        $studioSignuser->enrollid = $enrollid;
        $studioSignuser->name = $name;
        $studioSignuser->mobile = $mobile;
        $studioSignuser->QQ = $QQ;
        $studioSignuser->school = $school;
        $studioSignuser->ctime = $time;
        return $studioSignuser->save();
    }

    /**
     * 查看该用户是否已经购买过
     * @param type $uid
     * @param type $enrid
     */
    public static function getOrder($uid, $enrid) {
        return Orderinfo::find()->select('orderid')->where(['subjecttype' => 3])->andWhere(['mark' => $enrid])->andWhere(['uid' => $uid])->andWhere(['status' => 1])->asArray()->one();
    }

}
