<?php
namespace api\service;
use common\models\myb\Resource;
use Yii;
use common\redis\Cache;
use common\service\AliOssService;
/**
* 
*/
class ResourceService extends Resource
{
    /**
     * 获取单个图片详情
     * @param  [type] $rid [description]
     * @return [type]      [description]
     */
    public static function getResourceDetail($rid){
        $resource_detail_redis='resource_detail_';
        $rediskey=$resource_detail_redis.$rid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $resource_detail=$redis->hgetall($rediskey);    
        if (empty($resource_detail)) {
           $data=Resource::findOne(['rid'=>$rid])->attributes;
           $redis->hmset($rediskey,$data);
           $redis->expire($rediskey,3600*24*3);
           if(json_decode($data['img'])){
                $data['img']=json_decode($data['img']);
           }
           return $data; 
        }else{
            if(json_decode($resource_detail['img'])){
                $resource_detail['img']=json_decode($resource_detail['img']);
            }
           return $resource_detail;
        }
    }



    public static function uploadPicFile($ossobject,$file,$description=null){
        $model=new Resource();
        $model->description=$description;
        $model->resource_type=0;
        //检查图片大小和类型
        if($file['size']>10485760){
            $data['message']='图片太大';
            return $data;
        }

        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
        {
            $data['message']='图片格式错误';
            return $data;
        } 
        //开始处理图片
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::picUpload($ossobject, $filename, $file);
        if ($ret == false) {
            $data['message']='上传失败';
            return $data;
        }

        $img_infohw=AliOssService::getFileHW(Yii::$app->params['ossurl'] . $ret);
        //判断是否取到宽高
        if(!$img_infohw['height'] || !$img_infohw['width']){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        $img_info['n']['h']=$img_infohw['height'];
        $img_info['n']['w']=$img_infohw['width'];
        $img_info['n']['url']=Yii::$app->params['ossurl'] . $ret;
        //上传成功,写库
        $model->img=json_encode($img_info);
        $model->save();
        $data['img']=json_decode($model->img);
        $data['description']=$model->description;
        $data['rid']= $model->attributes['rid'];
        return $data;
    }

    /**
     * 语音上传
     * @param  [type] $file     [description]
     * @param  [type] $duration [description]
     * @return [type]           [description]
     */
    static function uploadTalkFile($ossobject,$file,$duration){
        //处理上传的图片
        if (!isset($_FILES['file'])) {
             $data['message']='未选择语音'; 
             return $data;
        }       
        $file = $_FILES['file'];
        //检查图片大小和类型
        if($file['size']>10485760){
              $data['message']='语音太大';
              return $data;
        }
        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".amr"]))
        {
             $data['message']='语音格式错误'; 
             return $data;
        } 
        //开始处理语音
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::talkUpload($ossobject, $filename, $file);
        $data['url']=Yii::$app->params['ossurl'] . $ret;
        $data['duration']=$duration;
        return $data;
    }

}