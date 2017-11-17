<?php
namespace mis\controllers\vesttweet;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TweetService;
use common\service\AliOssService;
use common\service\DictdataService;
use common\models\myb\Resource;

/**
 * 增加能力素材接口 
 */
class AddMaterialApiAction extends MBaseAction
{
    private $ossobject = 'tweet';
    /**
    *增加能力素材
     */
    
    public function run()
    {
        $request = Yii::$app->request;
        $title=$request->post('title');
        $content=$request->post('content');
        $f_catalog=$request->post('f_catalog');
        $s_catalog=$request->post('s_catalog');
        $ctime=strtotime($request->post('ctime'));
        if(empty($ctime)){
            $ctime=time();
        }
        $uid=$request->post('uid');
        if(empty($uid)){
             return $this->controller->outputMessage(['errno'=>1,'msg'=>'请选择用户']);
        }
        if(empty($content)){
             return $this->controller->outputMessage(['errno'=>1,'msg'=>'请输入内容']);
        }
        $tags=$request->post('tags');

        $request = Yii::$app->request;
        //只能post访问
        if(!$request->isPost){
            die('访问错误');
        }
       
        //处理上传的图片
        if (!isset($_FILES['uploadify'])) {
            die('未选择图片!');
        }       
        $file = $_FILES['uploadify'];
        //检查图片大小和类型
        if($file['size']>2048000){
            die('图片太大');
        }
        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
        {
            die('图片格式错误');
        }
        //开始处理图片
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
        if ($ret == false) {
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
       

        $picurl = Yii::$app->params['ossurl'] . $ret;
        //图片宽高
        $ret =  AliOssService::getFileHW($picurl);

        //判断是否取到宽高
        if(!$ret['height'] || !$ret['width']){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        if($ret!=false){
            $data['w'] = $ret['width'];
            $data['h'] = $ret['height'];
        }else{
            $data['w'] = 1000;
            $data['h'] = 1000;
        }
        $data['url'] = $picurl;
        $img['n']=$data;
        $model=new Resource();
        $model->img=json_encode($img);
        if($model->save()){
           $rid=$model->attributes['rid'];
        }else{
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败(入库)']);
        }
        

         //上传成功,写库
        $tmodel = new TweetService();
        $tmodel->f_catalog=$f_catalog;
        $tmodel->s_catalog=$s_catalog;

        $tmodel->f_catalog_id = null;
        $tmodel->s_catalog_id = null;
        if($tmodel->f_catalog){
            $tmodel->f_catalog_id = DictdataService::getTweetMainTypeIdByName($tmodel->f_catalog);
            if($tmodel->s_catalog){
                $tmodel->s_catalog_id = DictdataService::getTweetSubTypeIdByName($tmodel->f_catalog_id, $tmodel->s_catalog);
            }                   
        }

        $tmodel->title=$title;
        $tmodel->content=$content;
        $tmodel->type=1;
        $tmodel->uid=$uid;
        $tmodel->tags=$tags;
        $tmodel->resource_id=(string)$rid;
        $tmodel->is_del=0;
        $tmodel->hits=0;

        $tmodel->ctime=$ctime;
        $tmodel->utime=$ctime;
        if($tmodel->save()){
            return $this->controller->outputMessage(['errno'=>0]);
        }
        else{
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'保存数据库失败']);
        }       
    


    }
}
