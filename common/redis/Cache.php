<?php

namespace common\redis;

use Yii;
use yii\base\InvalidConfigException;

/**
 * 继承vendor redis，重写key的生成方法，自己控制key，禁止md5加密
 * @author Administrator
 *
 */
class Cache extends \yii\redis\Cache
{
    public function init()
    {
        parent::init();
    }
    
    /**
     * 重写buildKey方法，不用md5加密
     */
    public function buildKey($key)
    {
    	return $key;
    }
    
    /**
     * lpush 方法,暂时不考虑超长问题
     * @param unknown $key
     * @param unknown $value
     */
    public function lpush($key,$value){
    	return (bool) $this->redis->executeCommand('LPUSH', [$key, $value]);
    }

    public function lrange($key,$start,$end){
    	return $this->redis->executeCommand('LRANGE',[$key,$start,$end]);
    }
    
    /**
     * 删除列表里的元素,根据参数 count 的值，移除列表中与参数 value 相等的元素
     * count > 0 : 从表头开始向表尾搜索，移除与 value 相等的元素，数量为 count 。
     * count < 0 : 从表尾开始向表头搜索，移除与 value 相等的元素，数量为 count 的绝对值。
     * count = 0 : 移除表中所有与 value 相等的值。
     * @param unknown $key
     * @param unknown $count
     * @param unknown $value
     */
    public function lrem($key,$count,$value){
    	return $this->redis->executeCommand('LREM',[$key,$count,$value]);
    }
    
    /**
     * @param unknown $key
     * @param unknown $field
     * @param unknown $num
     * @param string $isExists 代表是否判断key存在
     * @return boolean
     */
    public function hincrby($key,$field,$num,$isexists=true){
    	if($isexists && ($this->exists($key)==0)){
    		return false;
    	}
        return (bool) $this->redis->executeCommand('HINCRBY', [$key, $field, $num]);
    }
    
    public function rpop($key){
    	return $this->redis->executeCommand('RPOP',[$key]);
    }
    
    public function hset($key,$field,$value){
    	return $this->redis->executeCommand('HSET',[$key, $field,$value]);
    }

    public function rpush($key,$field){
        return $this->redis->executeCommand('RPUSH',[$key, $field]);
    }

    /**
     * 
     * @param unknown $key
     * @param unknown $field
     */
    public function hget($key,$field){
    	return $this->redis->executeCommand('HGET',[$key, $field]);
    }
    
    public function hmset($key,$datas){
    	$arr = [$key];
    	foreach ($datas as $k=>$v){
    		$arr[] = $k;
    		$arr[] = $v;
    	}
    	return $this->redis->executeCommand('HMSET',$arr);
    }
    
    public function hmget($key,$fields){
    	$arr = [$key];
    	foreach ($fields as $k=>$v){
    		$arr[] = $v;
    	}
    	return $this->redis->executeCommand('HMGET',$arr);
    }
    
    /**
     * getall返回的数组中包括了key和value，需要解析成键值对数组
     * @param unknown $key
     */
    public function hgetall($key){
    	$ret = $this->redis->executeCommand('HGETALL',[$key]);
    	
    	if(!$ret){
    		//未取到
    		return $ret;
    	}
    	//hgetall命令返回的一定是数组，并且长度为偶数
    	$retcount = count($ret);
    	if (($retcount%2) != 0){
    		return false;
    	}
    	//解析返回数组成键值对
    	$i=0;
    	$arr = [];
    	foreach ($ret as $k=>$v){
    		if($i==0){
    			//第一次
    			$key = $v;
    		}	
    		else{
    			$arr[$key] = $v;
    		}
    		$i++;
    		if($i>1){
    			$i=0;
    		}
    	}    	
    	
    	return $arr;
    }
    
    /**
     * zadd单条增加数据
     * @param unknown $key
     * @param unknown $score
     * @param unknown $member
     */
    public function zadd($key,$score,$member){
    	$params[] = $key;
    	$params[] = $score;
    	$params[] = $member;
    	return $this->redis->executeCommand('ZADD',$params);
    }
    
    /**
     * zadd批量添加数据
     * $data是score和member的集合数组
     * @param unknown $key
     * @param unknown $data data的key必须是score和member
     */
    public function zadd_arr($key,$data){
    	//data必须是数组
    	if(!is_array($data)){
    		return false;
    	}
    	$params[] = $key;
    	foreach ($data as $v){
    		$params[] = $v['score'];
    		$params[] = $v['member'];
    	}
    	return $this->redis->executeCommand('ZADD',$params);
    }
    
    /**
     * 
     * @param unknown $key
     * @param unknown $start
     * @param unknown $stop
     * @param string $withscores
     */
    public function zrange($key,$start,$stop,$withscores=false){
    	$params = [];
    	 
    	$params[] = $key;
    	//取值范围
    	$params[] = $start;
    	$params[] = $stop;
    	//判断WITHSCORES
    	if($withscores){
    		$params[] = 'WITHSCORES';
    	}
    	$ret = $this->redis->executeCommand('ZRANGE',$params);
    	if($withscores){
    		//解析带score的返回结果，key存member,value存score
    		$i=0;
    		$arr = [];
    		foreach ($ret as $k=>$v){
    			if($i==0){
    				//第一次
    				$key = $v;
    			}
    			else{
    				$arr[$key] = $v;
    			}
    			$i++;
    			if($i>1){
    				$i=0;
    			}
    		}
    		return $arr;
    	}
    	return $ret;
    }
    
    /**
     * 根据score排序，暂时先不支持WITHSCORES
     * @param unknown $key
     * @param unknown $min -inf +inf 分别代表最小和最大值，
     * @param unknown $max
     * @param string $limit 数组，不为null时表示offset 和count
     */
    public function zrangebyscore($key,$min,$max,$limit=null,$withscores=false){
    	$params = [];
    	
    	$params[] = $key;
    	//score的范围
    	$params[] = $min;
    	$params[] = $max;
    	//判断WITHSCORES
    	if($withscores){
    		$params[] = 'WITHSCORES';
    	}
    	//判断limit条件
    	if(isset($limit) && is_array($limit)){
    		$params[] = 'LIMIT';
    		$params[] = $limit[0]; //offset
    		$params[] = $limit[1]; //count
    	}
    	$ret = $this->redis->executeCommand('ZRANGEBYSCORE',$params);
    	if($withscores){
    		//todo 解析带score的返回结果
    	}
    	return $ret;
    }

    public function zscore($key,$member){
    	return $this->redis->executeCommand('ZSCORE',[$key,$member]);
    }
    
    
    public function zcount($key,$min,$max){
    	return $this->redis->executeCommand('ZCOUNT',[$key,$min,$max]);
    }    
    
    public function zincrby($key,$score,$member){
    	return $this->redis->executeCommand('ZINCRBY',[$key,$score,$member]);
    }
    
    public function zrem($key,$member){
    	return $this->redis->executeCommand('ZREM',[$key,$member]);
    }
    
    public function expire($key,$seconds){
    	return $this->redis->executeCommand('EXPIRE',[$key,$seconds]);
    }
    
    public function exists($key){
    	return $this->redis->executeCommand('EXISTS',[$key]);
    }
    
	public function getValue($key)
    {
        return $this->redis->executeCommand('GET', [$key]);
    }
    
    public function setValue($key, $value, $expire)
    {
        if ($expire == 0) {
            return (bool) $this->redis->executeCommand('SET', [$key, $value]);
        } else {
            $expire = (int) ($expire * 1000);
            return (bool) $this->redis->executeCommand('SET', [$key, $value, 'PX', $expire]);
        }
    }
    
    /**
     * 清除当前database的所有缓存
     * @return Ambigous <multitype:, boolean, NULL, string, \yii\redis\mixed>
     */
    public function flushdb()
    {
    	return $this->redis->executeCommand('FLUSHDB');
    }
}
