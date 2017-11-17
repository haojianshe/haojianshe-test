<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;
/**
 * 自动创建controllers和views
 *   1.需更改继承类
 *   2.更改目录名称
 *   3.更改资源权限
 */
class AddController extends MBaseController
{	
    //去掉csrf验证，不然post请求会被过滤掉
    public $enableCsrfValidation = false;
    //存储文件夹
    public $folder="adv";
    //资源配置
    public $resource_id="operation_adv";
    //Controller 类
    public $controller_name="AdvController";
    public function actionIndex()
    {
      //项目所在目录
      $path= Yii::$app->BasePath;
      //引入要生成的Controller
      require_once("$path/controllers/$this->controller_name.php"); 
      $controller_obj=new AdvController(1,1);
      //获取controller 的Action
      $action=$controller_obj->actions();
      //循环action 生成controller和view
      foreach ($action as $key => $value) {
          foreach ($value as $key1 => $value1) {
              $controller=str_replace("\\", "", strrchr($value1, "\\"));
              $view=strtolower(str_replace("Action", "", $controller));
              //创建控制器action
              $this->addController($path,$this->folder,$controller,$this->resource_id,$view);
              //创建视图            
              $this->addView($path,$this->folder,$view);
          }
      }
    }
    /**
     * 创建controller action
     * @param [type] $path        [description]
     * @param [type] $folder      [description]
     * @param [type] $controller  [description]
     * @param [type] $resource_id [description]
     */
    private function addController($path,$folder,$controller,$resource_id,$view){
      //action 所在目录
      $controllerPath="$path/controllers/$folder/";
      $enter="\r\n";
      $content= '';
      $content.="<?php".$enter;
      $content.="namespace mis\controllers\\$folder;".$enter.$enter;
      $content.="use Yii;".$enter;
      $content.="use mis\components\MBaseAction;".$enter.$enter;
      $content.="class $controller extends MBaseAction".$enter;
      $content.="{".$enter;
      $content.="  //在配置文件中配置的resource对应的参数名字".$enter;
      $content.="  public \$resource_id = '".$resource_id."';".$enter;
      $content.="  public function run()".$enter;
      $content.="    {".$enter;
      $content.="       return \$this->controller->render('$view'); ".$enter;
      $content.="    }".$enter;
      $content.="}".$enter;
      $this->addContentToPhpFile($controllerPath,$controller,$content);
    }
    /**
     * 增加视图文件
     * @param [type] $path   [description]
     * @param [type] $folder [description]
     * @param [type] $view   [description]
     */
    private function addView($path,$folder,$view){
      //action 所在目录
      $controllerPath="$path/views/$folder/";
      $content="<?php echo '$view';";
      //创建目录
      $this->addContentToPhpFile($controllerPath,$view,$content);
    }
    /**
     * 创建文件保存php
     * @param [type] $path     [description]
     * @param [type] $filename [description]
     * @param [type] $content  [description]
     */
    private function addContentToPhpFile($path,$filename,$content){
      if (!file_exists($path)){ 
        mkdir($path);
      }
      //若文件不存在则创建
      $file_path="$path$filename.php";
      if(!file_exists($file_path)){
        echo $file_path.'</br>';
        //创建对应action
        file_put_contents($file_path, $content);
      }else{
        echo '已存在-----'.$file_path.'</br>';
      }
    }
}