<?php
namespace common\service;

use Yii;
use yii\base\Object;

/**
 * 公共方法类
 */
class CommonFuncService extends Object
{   
	//获取不同尺寸图片的方法**************************************************** begin ***
	/**
	 * 获取阿里云相应尺寸的图片
	 * @param unknown $arrsource 原图数据包括url 原始宽度 原始高度
	 * @param unknown $type
	 * @return Ambigous <unknown, multitype:string number >
	 */
	static function getPicByType($arrsource,$type){
		//默认返回't'类型的图片，最小
		$getHeight = 200;
		$sourceHeight = $arrsource['h'];
		$sourceWidth = $arrsource['w'];
	
		if($type == 'l'){
			$getHeight = 400;
		}
		if($type == 's'){
			$getHeight = 800;
		}
		if ($sourceHeight > $getHeight) {
			//原图尺寸大于要取的尺寸
    		$result = array(
    				'url' => $arrsource['url'].'@'.$getHeight.'h_2o',
    				'h' => $getHeight,
    				'w' => intval($getHeight * $sourceWidth / $sourceHeight)
    		);
    	} else {
    		//原图尺寸小于要取的尺寸
    		$result = $arrsource;
    	}
    	return $result;
	}
	
	/**
	 * 根据图片原始信息取相应的值
	 * @param unknown $url
	 * @param unknown $sourcew 原图宽度
	 * @param unknown $sourceh 原图高度
	 * @param unknown $type    想要的图片类型
	 * @return Ambigous <unknown, multitype:string number >
	 */
	static function getPicByType2($url,$sourcew,$sourceh,$type){
		//原图
		$source = ['url' => $url,
					'h' => $sourceh,
					'w' => $sourcew];
		//取原图
		if($type=='n'){
			return $source;	
		}
		//其他尺寸
		return static::getPicByType($source, $type);
	}
	//获取不同尺寸图片的方法**************************************************** end ***
   


    /**
     * [格式化时间 传入时间戳返回格式化后时间]
     * @param  [type]  $timestamp    [时间戳]
     * @param  integer $current_time [默认取当前时间]
     * @return [type]                [description]
     */
    static function format_time($timestamp, $current_time = 0) {
        if(!$current_time)
            $current_time = time();
        $span = $current_time - $timestamp;
        $format_time = '';
        if($span < 60) {
            $format_time = "刚刚";
        }elseif($span < 3600) {
            $format_time = intval($span/60) . "分钟前";
        }elseif($span < 24*3600) {
            $format_time = intval($span/3600)."小时前";
        }elseif($span < (7*24*3600)) {
            $format_time = intval($span/(24*3600))."天前";
        }else{
            $format_time = date('Y-m-d',$timestamp);
        }
        return $format_time;
    } 
    
    /**
     * 解析新闻里的bbcode字符
     * @param unknown $content
     */
    static function news_analysis($content){
    	//(1)处理乐视视频
    	//匹配所有乐视视频的bbcode
    	$num = preg_match_all("/\[levedio\](.*?)\[\/levedio\]/is", $content, $arr1);
    	if($num==0){
    		return $content;
    	}    	
    	//将每一个视频替换为html代码
    	for($i=0;$i<$num;$i++){
    		//获取参数
    		$var=[];
    		$var['auto'] = 0;
    		$num1 = preg_match("/\{(.*?)\}/is",$arr1[1][$i],$arr2);
    		if($num1==0){
    			//未匹配到参数串
    			continue;
    		}
	    	$vartmp1 = explode(',', $arr2[1]);
    		foreach ($vartmp1 as $k=>$v){
    			$vartmp2 = explode(':', $v);
    			if(count($vartmp2)>=2){
    				$var[$vartmp2[0]] = $vartmp2[1];
    			}
    		}
    		//替换成播放代码
    		if(isset($var['uu']) && isset($var['vu'])){
    			$str = "<div style='width:100%;height:450px;'>
    					<script type='text/javascript'> 
    					var letvcloud_player_conf =  {'uu':'" . $var['uu'] . "','vu':'" . $var['vu'] . "','auto_play':" . $var['auto'] . ",'gpcflag':1};
    					</script><script type='text/javascript' src='https://yuntv.letv.com/player/vod/bcloud.js'></script> </div>";
    			$content = str_replace($arr1[0][$i],$str,$content);
    		}
    	}
    	return $content;
    }


    /**
     * [reSizeImg description]
     * @param  [type] $img  [二进制图片流]
     * @param  [type] $wid  [缩放宽度]
     * @param  [type] $hei  [缩放高度]
     * @param  [type] $c    [是否裁剪1/0 截图/不截图]
     * @param  [type] $murl [生成图片地址（可传原图片地址会替换原图）]
     * @param  [type] $quality [生成图片质量1-100 越大质量越好]
     * @return [type]       [description]
     */
   public static function reSizeImg($img, $wid, $hei,$murl,$quality=100,$c=0)
   {
       $resize_width = $wid;  
       $resize_height = $hei;  
       $cut = $c;  
       // 从字符串中的图像流新建一图像
       $im=imagecreatefromstring($img);
       //生成图片地址
       $dstimg = $murl;

       //图片原始宽高
       $width = imagesx($im);  
       $height = imagesy($im);  
       
       //改变后的图象的比例  
       $resize_ratio = ($resize_width)/($resize_height);  
       //实际图象的比例  
       $ratio = ($width)/($height);  
       if(($cut)=="1")  
       //裁图  
       {
           if($ratio>=$resize_ratio)  
           //高度优先  
           {
               $newimg = imagecreatetruecolor($resize_width,$resize_height);
               imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width,$resize_height, (($height)*$resize_ratio), $height); 
               ImageJpeg ($newimg,$dstimg,$quality);
           }
           if($ratio<$resize_ratio)   
           //宽度优先  
           {
               $newimg = imagecreatetruecolor($resize_width,$resize_height);
               imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width, $resize_height, $width, (($width)/$resize_ratio));
               ImageJpeg ($newimg,$dstimg,$quality);  
           }
       }
       else  
       //不裁图  
       {  
           if($ratio>=$resize_ratio)  
           {  
               $newimg = imagecreatetruecolor($resize_width,($resize_width)/$ratio); 
               $res=imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width, ($resize_width)/$ratio, $width, $height);
               ImageJpeg ($newimg,$dstimg,$quality);  
           }  
           if($ratio<$resize_ratio)  
           {  
               $newimg = imagecreatetruecolor(($resize_height)*$ratio,$resize_height);
               imagecopyresampled($newimg, $im, 0, 0, 0, 0, ($resize_height)*$ratio, $resize_height, $width, $height);  
               ImageJpeg ($newimg,$dstimg,$quality);  
           }  
       }
      ImageDestroy($im);
   }
   
   
    /**
     * 获取某年的每一周第一天和最后一天
     * @param  [int] $year [年份]
     * @return [arr]       [每周的周一和周日]
     */
    public static function getWeek($year) {
        $year_start = $year . "-01-01";
        $year_end = $year . "-12-31";
        $startday = strtotime($year_start);
        if (intval(date('N', $startday)) != '1') {
            $startday = strtotime("next monday", strtotime($year_start)); //获取年第一周的日期
        }
        $year_mondy = date("Y-m-d", $startday); //获取年第一周的日期

        $endday = strtotime($year_end);
        if (intval(date('W', $endday)) == '7') {
            $endday = strtotime("last sunday", strtotime($year_end));
        }
        $num = intval(date('W', $endday));
        for ($i = 1; $i <= $num; $i++) {
            $j = $i - 1;
            $start_date1 = strtotime(date("Y-m-d H:i:s", strtotime("$year_mondy $j week ")));
            $start_date = date("Y-m-d H:i:s", strtotime("$year_mondy $j week "));
            $end_day = strtotime(date("Y-m-d H:i:s", strtotime("$start_date +6 day")));
            #$week_array[$i] = array (str_replace("-",".",$start_date), str_replace("-", ".", $end_day));
            #$week_array[$i] = array ($start_date1,$end_day);
            //$week_array[$year][$i] = $start_date1 . '-' . $end_day;
            $week_array[$i] = array($start_date1,$end_day);
        }
        return $week_array;
    }
    /**
     * 获取指定月份的第一天开始和最后一天结束的时间戳
     *
     * @param int $y 年份 $m 月份
     * @return array(本月开始时间，本月结束时间)
     */
    public static function mFristAndLast($y = "", $m = "") {
        if ($y == "")
            $y = date("Y");
        if ($m == "")
            $m = date("m");
        $m = sprintf("%02d", intval($m));
        $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

        $m > 12 || $m < 1 ? $m = 1 : $m = $m;
        $firstday = strtotime($y . $m . "01000000");
        $firstdaystr = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));

        return array(
            "firstday" => $firstday,
            "lastday" => $lastday
        );
    }
    
    
    /**
     * 获取用户电话归属地
     */
    public static function getTelApi($mobile = '') {
        header('Content-type:text/html;charset=utf-8');
        $apiurl = 'http://apis.juhe.cn/mobile/get';
        $params = array(
            'key' => 'b4b88a8ffc09e2fd3f24251ee19fa168', //您申请的手机号码归属地查询接口的appkey
            'phone' =>$mobile //要查询的手机号码
        );
        $paramsString = http_build_query($params);
        $content = @file_get_contents($apiurl . '?' . $paramsString);
        $result = json_decode($content, true);
        if($result['result']['city']){
            return $result['result']['province'].'-'.$result['result']['city'];
        }else{
            return $result['result']['province'];
        }
        
    }
    
    //手机号验证
    static function check_mobile($mobile) {
    	$pattern = '/^[1][3-8]+\d{9}/i';
    	$preg_ret = preg_match_all($pattern, $mobile, $m);
    	if(false === $preg_ret || 0 == $preg_ret) {
    		return false;
    	}
    	return true;
    }
}
