<?php
namespace console\controllers\correcttask;

use Yii;
use yii\base\Action;

/**
 * 停止CorrectTask守护进程
 * @author Administrator
 *
 */
class StopAction extends Action
{
    public function run()
    {
    	//存储pid的文件路径
    	$pidfile = __DIR__ . '/../../correcttask.pid';
    	//清除文件状态缓存
    	clearstatcache();
    	if(!file_exists($pidfile)){
    		die('未发现push.pid文件'.PHP_EOL);
    	}
    	//读取pids
    	$pids_str = file_get_contents($pidfile);
    	$pids = explode(',',$pids_str);
    	foreach ($pids as $k=>$v){
    		if($v){
    			posix_kill($v, 9);
    			echo '关闭进程-'.$v .PHP_EOL;
    		}
    	}
    	//删除pid文件
    	unlink($pidfile);
    	die('CorrectTask守护进程成功关闭'.PHP_EOL);
    }
}
