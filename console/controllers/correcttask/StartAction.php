<?php
namespace console\controllers\correcttask;

use Yii;
use yii\base\Action;
use console\service\CorrectShareTaskService;
use console\service\CorrectTalkService;
use console\service\CorrectService;
use common\service\AliOssService;

/**
 * 启动批改分享守护进程
 */
class StartAction extends Action
{
    public function run()
    {
    	//获取要启动的进程数
    	$processnum = 1;
    	//强制休眠数，当进程处理到一定数量的数据时强制休眠，避免缓存数据太多卡系统
    	$sleepnum = 100;
    	//存储pid的文件路径
    	$pidfile = __DIR__ . '/../../correcttask.pid';
    	//清除文件状态缓存
    	clearstatcache();
    	if(!file_exists($pidfile)){
    		//没有标志文件，创建文件
    		file_put_contents($pidfile, '', LOCK_EX);
    		//改变文件的权限，否则file_exists函数无法判断pid是否存在
    		chmod($pidfile,0777);
    	}
    	else{
    		//有标志文件，判断是否正在运行中
   			die('程序正在运行中,请先关闭'.PHP_EOL);
    	}
    	//启动子进程
    	$pid = pcntl_fork();
    	if ($pid == -1) {
    		//启动子进程错误
    		die('could not fork');
    	} else if ($pid>0) {
    		//父进程代码逻辑,pid>0,记录子进程的pid,用于stop时kill
    		file_put_contents($pidfile, $pid.',', FILE_APPEND | LOCK_EX);
    	} else {
    		//子进程代码进入此逻辑pid=0
    		echo '启动进程-'. getmypid() . PHP_EOL;
    		//计数器，当处理到一定数量的推送消息时，休眠1秒，避免占用太多cpu资源
    		$num = 0;
    		$redis = Yii::$app->cachequeue;
    		$rediskey = 'correcttask';
    		while(true){
    			try {
    				//从缓存取数据
    				$params = $redis->rpop($rediskey);
    				//没有取到数据则sleep1秒
    				if(!$params){
    					sleep(1);
    					$num = 0;
    					continue;
    				}
    				//数据处理,根据任务的不同进行不同处理
    				$params = json_decode($params, true);
    				switch ($params['tasktype']){
    					case 'share':
    						//分享任务
    						$this->correctShare($params);
    						break;
    				}
    				//守护进程时，关闭redis连接，否则会引起服务器端连接数过高
    				$redis->redis->close();
    				//执行到最大处理数休眠一秒，避免卡系统
    				$num++;
    				if($num>$sleepnum){
    					sleep(1);
    					$num=0;
    				}
    			}
    			catch (Exception $e) {
    				echo $e;
    			}
    		}
    	}
    }
    
    /**
     * 系统消息离线任务的处理逻辑,区分推送类型转到相应的处理逻辑
     * 原系统会检查发送消息的频率，此版去掉，因为可能有一些来自mis的批量请求
     * custom的t参数是消息类型 1:推送消息 2:系统消息小红点 3:私信小红点
     * p包括j uid等参数,uid在发私信小红点消息时需要，系统消息小红点的时候没有,j在通知的时候需要,不同类型内容，现在只有私信和系统消息
     */
    private function correctShare($params){
    	$correctid = $params['correctid'];
    	
    	$taskmodel = CorrectShareTaskService::findOne(['correctid'=>$correctid]);
    	if(!$taskmodel || $taskmodel->issuccess){
    		//已经进行过mp3转化
    		return;
    	}    	
    	//(1)获取批改信息
    	$correctmodel= CorrectService::getDetail($correctid);
    	if(!$correctmodel){
    		return;
    	}
    	
    	$taskmodel->changetime=time();
    	$taskmodel->ischange = 1;
    	//(2)总评转mp3
    	$talkid = $correctmodel['majorcmt_id'];
    	if(! $this->amr2mp3($talkid) ){
    		//任务失败
    		$taskmodel->issuccess=0;
    		$taskmodel->save();
    		return;
    	}
    	//(3)点评转mp3
    	if($correctmodel['pointcmt_ids']){
    		$talkids = explode(',', $correctmodel['pointcmt_ids']);
    		if($talkids && count($talkids)>0){
    			foreach ($talkids as $k => $v) {
    				if(! $this->amr2mp3($v) ){
    					//任务失败
    					$taskmodel->issuccess=0;
    					$taskmodel->save();
    					return;
    				}			
    			}
    		}
    	}    	
    	//(4)任务成功
    	$taskmodel->issuccess=1;    	 
    	$taskmodel->save();    	
    	//print_r('correctid:'.$correctid.'   talkid:'.$maintalkid);
    }
    
    /**
     * amr 转 mp3
     * @param unknown $amrurl
     * @param unknown $mp3url
     */
    private function amr2mp3($talkid){
    	//临时文件地址
    	$dir = __DIR__ . '/../../runtime/correcttask/';
    	$ossurl =  'http://img.meiyuanbang.com/';
    	
    	$talkmodel = CorrectTalkService::findOne(['talkid'=>$talkid]);
    	//(3)获取amr文件转换成mp3
    	$url = $talkmodel->url;
    	$amrfile = $dir. $this->getName($url);
    	$mp3file = str_replace(".amr",".mp3",$amrfile);
    	$output = $this->downloadAmr($url);
    	if(!$output){
    		return;
    	}
    	file_put_contents($amrfile, $output);
    	//转mp3,需要用whereis 命令找到ffmpeg地址，
    	//写完整路径，否则在命令行启动服务后可以执行，在crontab中启动后报sh: ffmpeg: command not found
    	exec("/usr/local/bin/ffmpeg -i {$amrfile} {$mp3file} 2>&1", $re);
    	//(4)上传mp3文件
    	if(!file_exists($mp3file)){
    		//转mp3失败,记录日志,只记录最后一次失败log
    		file_put_contents($dir . 'error.log', $re, LOCK_EX);
    		return;
    	}
    	$mp3filename = AliOssService::getFileName('.mp3');
    	$ret = AliOssService::talkMp3Upload('correct/sound', $mp3filename, $mp3file);
    	//上传后删除amr和mp3文件
    	unlink($amrfile);
    	unlink($mp3file);
    	if ($ret == false) {
    		//失败
    		return false;
    	}
    	//更新语音信息
    	$talkmodel->mp3url = $ossurl.$ret;
    	$talkmodel->save();
    	//清语音缓存
    	CorrectTalkService::removeCache($talkid);
    	return true; 
    }
    
    /**
     * 从url地址中获取文件的名字
     * @param unknown $url
     */
    private function getName($url){
    	$arr = explode( '/' , $url );
    	$filename= $arr[count($arr)-1];
    	return $filename;
    }
    
    private function downloadAmr($url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$output = curl_exec($ch);	
    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    	if (200 != $http_code) {
    		return false;
    	} else {
    		return $output;
    	}
    }
}
