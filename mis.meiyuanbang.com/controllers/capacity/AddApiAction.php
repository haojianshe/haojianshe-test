<?php
namespace mis\controllers\capacity;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\dict\CapacityModelDictDataService;
use mis\service\CapacityModelMaterialService;
use common\service\AliOssService;
/**
 * 增加能力素材接口 
 */
class AddApiAction extends MBaseAction
{
    private $ossobject = 'capacity_material';
    /**
    *增加能力素材
     */
    
    public function run()
    {
        $request = Yii::$app->request;
        $f_catalog_id=$request->get('f_catalog_id');
        $s_catalog_id=$request->get('s_catalog_id');
        $uid=$request->get('uid');
        $content=$request->get('content');
        $item_id=$request->get('item_id');
        $tags=$request->get('checkboxId');
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
        //$ret =  AliOssService::getFileHW($picurl);
        //判断是否取到宽高
        //if(!$ret['height'] || !$ret['width']){
            $arr=getimagesize($file['tmp_name']);
            $pic_arr['n']['w']=$arr[0]; //宽度
            $pic_arr['n']['h']=$arr[1]; //高度
           // return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        //}
        $pic_arr["n"]['url']=$picurl;
        //$pic_arr['n']['w']=$ret['width'];
        //$pic_arr['n']['h']=$ret['height'];
         //上传成功,写库
        $model = new CapacityModelMaterialService();
        $model->f_catalog_id=$f_catalog_id;
        $model->s_catalog_id=$s_catalog_id;
        $model->item_id=$item_id;
        $model->picurl=json_encode($pic_arr);
        $model->status=0;
        $model->uid=$uid;
        $model->content=$content;
        $model->tags=$tags;
        $model->ctime=time();
        $model->utime=time();
        if($model->save()){
            return $this->controller->outputMessage(['errno'=>0]);
        }
        else{
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'保存数据库失败']);
        }       
    


    }
}
