<?php
namespace mis\controllers\tweet;

use Yii;
use mis\components\MBaseAction;


use mis\models\UploadForm;
use yii\web\UploadedFile;
require_once __DIR__ . '/../../../vendor/phpexcel/PHPExcel.php';
/**
 *删除帖子
 */
class ImportExcelAction extends MBaseAction
{ 
    /**
     * 只支持post删除
     */
    public function run()
    {  $request = Yii::$app->request;
         
       //从post获取参数
       $folder_name = $request->get("folder_name");
       $model = new UploadForm();
       $sheetData  =array();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {  
                //判断是否是Excel 文件
                if($model->file->extension== 'xlsx' or $model->file->extension=='xls'){
                    $result= $model->file->saveAs('uploads/' . iconv("UTF-8","GB2312",$model->file->baseName)  . '.' . $model->file->extension);
                    //判断文件是否上传成功
                    if($result){
                        echo $model->file->baseName. '.'.$model->file->extension.'  上传成功'.'</br>';
                        /* start 得到Excel 文件里的数据转化成数组*/
                        $filename=__DIR__ . '/../../web/uploads/'.iconv("UTF-8","GB2312",$model->file->baseName). '.'.$model->file->extension; //Excel地址
                       // $imghost='http://img.meiyuanbang.com/tweet/1000-00-00/';//图片在阿里云存储的地址
                        $encode='utf-8';
                        //区分Excel 版本
                        $extension=substr(strrchr($filename, '.'), 1);        
                        if( $extension =='xlsx' ){
                            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                        }else{
                            $objReader = \PHPExcel_IOFactory::createReader('Excel5');;
                        }
                        $objReader->setReadDataOnly(true);
                        $objPHPExcel = $objReader->load($filename);
                        $sheetData  = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                        /* end 得到Excel 文件里的数据转化成数组*/
                        //处理得到的Excel 数据 插入到相应的表里
                        /*$count = count($sheetData);
                        if($count>200){
                            echo '文件行数大于200，s无法处理';
                            return $this->controller->render('uploaddatafile', ['model' => $model]);
                            exit;
                        }
                        for($i=2;$i<=$count;$i++)
                        {
                            $tweettemp= new TempTweet();
                            $keys=array_keys($sheetData[$i]); 
                            $resource= new ResourceService();
                            //获取图片信息保存到评论表 得到评论id
                            $img_infohw = AliOssService::getFileHW($sheetData[$i][$keys[1]]);
                            if(!$img_infohw){ 
                                echo '内容为'.$sheetData[$i][$keys[0]].'的帖子: 图片错误';
                                continue ;}
                            $img_info['n']['h']=$img_infohw['height'];
                            $img_info['n']['w']=$img_infohw['width'];
                            $img_info['n']['url']=$sheetData[$i][$keys[1]];
                            $resource->img=json_encode($img_info);
                            $resource->resource_type=0;
                            $resource->save();
                            //得到评论id 保存到临时帖子表
                            $tweettemp->resource_id=$resource->attributes['rid'];
                            $tweettemp->content=$sheetData[$i][$keys[0]];
                            $tweettemp->imgurl=$sheetData[$i][$keys[1]];
                            $tweettemp->f_catalog=$sheetData[$i][$keys[2]];
                            $tweettemp->s_catalog=$sheetData[$i][$keys[3]];                            
                            $tweettemp->tags=$sheetData[$i][$keys[4]];                           
                            $tweettemp->flag=0;
                           // $tweettemp->save();
                          //  echo '插入了内容为：'.$tweettemp->content.'的帖子</br>';
                            for($j=5;$j<count($keys);$j++){
                                //保存评论数据
                                if(!empty($sheetData[$i][$keys[$j]])){
                                    $commenttemp=new TempComment();
                                    $commenttemp->temptid=$tweettemp->attributes['temptid'];
                                    $commenttemp->content=$sheetData[$i][$keys[$j]];
                                    $commenttemp->flag=0;
                                  //  $commenttemp->save();
                                    //echo '插入了评论：'.$sheetData[$i][$keys[$j]] .'</br>';
                                }                   
                            }
                            //echo'</br>';
                        }*/
                        //exit;
                    }else{
                       // echo $file->baseName . '.'.$file->extension.'  上传失败'.'</br>';
                    }
                }else{
                    echo '请上传Excel格式数据';
                }                
            }
        }
        //echo '上传Excel文件前,要将帖子图片上传到阿里云服务器';
        return $this->controller->render('uploaddatafile', ['model' => $model,'data'=>$sheetData,'folder_name'=>$folder_name]);
    }
}
