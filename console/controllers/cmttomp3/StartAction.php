<?php
namespace console\controllers\cmttomp3;

use Yii;
use yii\base\Action;
use common\service\AliOssService;
use console\service\CommentService;
/**
 * 启动评论转语音守护进程 
 * /home/web/backcode/pushservice cmttomp3/stop && nohup /home/web/backcode/pushservice cmttomp3/start >/dev/null 2>&1 
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
    	$pidfile = __DIR__ . '/../../cmttomp3.pid';
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
    		$rediskey = 'cmtsoundtomp3';
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
                    //评论主题的类型 0帖子 1专家动态评论 2小组讨论 3 文章 4考点 5活动 6 正能文章7、活动文章 8、活动问答
    				switch (intval($params['tasktype'])){
                        case 8:
    						//
    						$this->cmtToMp3($params);
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
    private function cmtToMp3($params){
    	$cid = $params['cid'];
    	$cmtmodel=CommentService::findOne(['cid'=>$cid]);
        if(empty($cmtmodel) ||intval($cmtmodel->ctype)!=2){
            //评论id错误
            return ;
        }
        if(! $this->cmtamr2mp3($cid) ){
            //任务失败
            return;
        }
    }
    
    /**
     * amr 转 mp3
     * @param unknown $amrurl
     * @param unknown $mp3url
     */
    private function cmtamr2mp3($cid){
    	//临时文件地址
    	$dir = __DIR__ . '/../../runtime/cmtsound/';
    	$ossurl =  'http://img.meiyuanbang.com/';
    	$cmtmodel = CommentService::findOne(['cid'=>$cid]);
    	//(3)获取amr文件转换成mp3
        //{"url":"http:\/\/img.meiyuanbang.com\/cmt\/2016-11-16\/4CCC5FFA83EBD29E3BD4756634FA9D59.amr","duration":1}
    	$amrcontent =json_decode($cmtmodel->content);

        $url=$amrcontent->url;
        
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
    	$ret = AliOssService::talkMp3Upload('cmt/sound', $mp3filename, $mp3file);
    	//上传后删除amr和mp3文件
    	unlink($amrfile);
    	unlink($mp3file);
    	if ($ret == false) {
    		//失败
    		return false;
    	}
    	//更新语音信息
    	$amrcontent->mp3url = $ossurl.$ret;
        $cmtmodel->content=json_encode($amrcontent);
    	$cmtmodel->save();
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
