<?php
namespace api\service;
use common\models\myb\CourseSectionVideo;
use Yii;
use common\redis\Cache;
use api\service\VideoResourceService;
use api\service\OrdergoodsService;
use common\service\DictdataService;

/**
* 课程章节视频相关方法
*/
class CourseSectionVideoService extends CourseSectionVideo
{
    /**
     * 获取详情
     */
    public static function getDetail($coursevideoid,$uid=-1){
        $rediskey="course_section_video_".$coursevideoid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail=$redis->hgetall($rediskey);
        if (empty($detail)) {
           $detail=self::find()->where(['coursevideoid'=>$coursevideoid])->asArray()->one();
           if($detail){
                $redis->hmset($rediskey,$detail);
                $redis->expire($rediskey,3600*24*3);
           }
        }
        $detail['buy_status']=OrdergoodsService::getByGoodStatus($uid,2,$coursevideoid);
        $detail['productid'] =DictdataService::getIosProductidByPrice($detail['ios_price']);

        $detail['video_info']=VideoResourceService::getDetail($detail['videoid']);
        //付费课程 未购买处理
        /*if($detail['buy_status']==1 && $detail['sale_price']>0){
            $detail['video_info']['sourceurl']='';
            $detail['video_info']['m3u8url']='';
        }*/
        return $detail;
    }
    /**
     * 章节视频列表
     * @param  [type] $sectionid [description]
     * @return [type]            [description]
     */
    public static function getSectionVideoList($sectionid){
        $redis = Yii::$app->cache;
        $rediskey="section_video_".$sectionid;
       // $redis->delete($rediskey);
        $ids=$redis->get($rediskey);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($ids)){
            $model=self::getSectionVideoListDb($sectionid);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['coursevideoid'].',';
                //$ret = $redis->rpush($rediskey, $value['coursevideoid'],true);
            }
            $ids=substr($ids, 0,strlen($ids)-1);
            $redis->set($rediskey, $ids);
            $redis->expire($rediskey,3600*24*3);
        }
        $list_arr=explode(',', $ids);
        return $list_arr;
    }
    /**
     * 数据库获取章节列表
     * @param  [type] $sectionid [description]
     * @return [type]            [description]
     */
    public static function getSectionVideoListDb($sectionid){
        return self::find()->select('coursevideoid')->where(['sectionid'=>$sectionid])->andWhere(['status'=>1])->orderBy("section_video_num asc")->asArray()->all();
    }
    /**
     * 列表获取视频详情
     * @param  [type] $coursevideoids [description]
     * @param  [type] $uid            [description]
     * @return [type]                 [description]
     */
    public static function getSectonVideoListDetail($coursevideoids,$uid){
        $ret_list=[];
        foreach ($coursevideoids as $key => $value) {
            $ret_list[]=self::getDetail($value,$uid);
        }
        return $ret_list;
    }
    /**
     * 通过视频章节获取视频信息
     * @param  [type] $sectionid [description]
     * @param  [type] $uid       [description]
     * @return [type]            [description]
     */
    public static function getVideosInfoBySectionid($sectionid,$uid){
        $videoids=self::getSectionVideoList($sectionid);
        $video_list=self::getSectonVideoListDetail($videoids,$uid);
        return $video_list;
    }
    /**
     * 获取订单视频详细信息
     * @param  [type] $coursevideoid [description]
     * @return [type]                [description]
     */
    public static function getOrderCourseVideoInfo($coursevideoid){
        return (new \yii\db\Query())
                ->select("a.coursevideoid,b.title,a.sale_price,a.ios_price,a.title as video_title,a.section_video_num,c.section_num,a.courseid")
                ->from(CourseSectionVideo::tableName()." as a")
                ->innerJoin("myb_course as b","a.courseid=b.courseid")
                ->leftJoin("myb_course_section as c","c.sectionid=a.sectionid")
                ->where(['coursevideoid'=>$coursevideoid])
                ->one();
    }

    /**
     * 获取视频详细信息
     * @param  [type] $coursevideoid [description]
     * @return [type]                [description]
     */
    public static function getCourseVideoInfoByVideo($coursevideoid){
        return (new \yii\db\Query())
                ->select("b.courseid as courseid")
                ->from(CourseSectionVideo::tableName()." as a")
                ->innerJoin("myb_course as b","a.courseid=b.courseid")
                ->leftJoin("myb_course_section as c","c.sectionid=a.sectionid")
                ->where(['coursevideoid'=>$coursevideoid])
                ->one()['courseid'];
    }
    /**
     * 根据一二级分类获取付费（1）/免费（0）视频课程
     * @param  [type] $f_catalog_id [description]
     * @param  [type] $s_catalog_id [description]
     * @param  [type] $status       [description]
     * @return [type]               [description]
     */
    public static function getIsPrizeCourseid($f_catalog_id=0,$s_catalog_id=0,$status=0,$limit=1,$notIn=[]){
        $wheresql='';
        if($f_catalog_id){
            $wheresql.=" and f_catalog_id=$f_catalog_id";
        }
        if($s_catalog_id){
            $wheresql.=" and s_catalog_id=$s_catalog_id";
        }
        if($notIn){
            $wheresql.=" and  a.courseid not in (".implode(",",$notIn).")";
        }
        $havingsql='';
        //是否付费
        if($status==0){
            $havingsql.="=0";
        }else{
            $havingsql.=">0";
        }
        
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("select a.courseid,b.status from myb_course_section_video as a inner join myb_course as b on a.courseid=b.courseid where b.status=2 $wheresql group by a.courseid having sum(price)$havingsql order by rand() limit $limit;");
        $data = $command->queryAll();
        $ret_courseid=[];
        if($data){
            foreach ($data as $key => $value) {
                $ret_courseid[]=$value['courseid'];
            }
        }
        
        return $ret_courseid;
       
    }
}