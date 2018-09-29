<?php
/**
 * Session令牌类
 * 利用Redis模拟session实现令牌
 * @author zhangdongyu
 *
 */
class Utils_SessionManager
{
	private $redisKey = ''; //RedisSession中存储的主Key
	private $life = 1800;	//生命周期
	private $redis = '';	//Redis

	public function __construct($life=1800)
	{
		$this->life = $life;

		$this->redis = ZOL_Api::run("Kv.Redis.getObj" , array(
				'serverName'     => 'Default',       #服务器名
		));
		$this->redisKey = $this->getSessionId();
	}

	/**
	 * 获取SessionId
	 * @return string sessionId
	 */
	private function getSessionId()
	{
		$sessionId = isset($_COOKIE['RSessionID'])?$_COOKIE['RSessionID']:'';
		$isExist = $this->redis->exists($sessionId);//判断缓存中hashmap是否存在
		if($sessionId&&$isExist) //session存在，且Redis中也存在，直接返回
		{
			setcookie("RSessionID", $sessionId, time()+$this->life,'/');//设置cookie值
			$this->redis->expire($sessionId,$this->life);
			return $_COOKIE['RSessionID'];
		}
		else	//创建新session
		{
			$sessionId = $this->generateKey(15);
			setcookie("RSessionID", $sessionId, time()+$this->life,'/');//设置cookie值
			$this->redis->hset($sessionId,'ctime',time()); //Redis创建session对应的hashmap
			$this->redis->expire($sessionId,$this->life);
			return $sessionId;
		}
	}

	/**
	 * 设置Session值
	 * @param string $key 	session键
	 * @param string $value	session值
	 */
	public function put($key,$value)
	{
		$this->redis->hset($this->redisKey,$key,$value);
	}

	/**
	 * 获取Session值
	 * @param string $key session键
	 */
	public function get($key)
	{
		$sessionValue = $this->redis->hget($this->redisKey,$key);
		return $sessionValue;
	}

	/**
	 * 删除Session值
	 * @param string $key
	 */
	public function del($key)
	{
		$this->redis->hdel($this->redisKey,$key);
	}


	/**
	 * 生成session随机key
	 * @param number $length 随机串长度
	 * @param string $isnum	是否为纯数字
	 * @return string	随机串
	 */
	public function generateKey($length = 10,$isnum=false) {
		if($isnum){
			$pattern = '1234567890';//纯数字
		}else{
			$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ'; //字符池
		}
		$key = '';
		for($i = 0; $i < $length; $i ++) {
			$key .= $pattern {mt_rand ( 0, strlen($pattern)-1)}; //生成php随机数
		}
		return $key;
	}
	
	/**
	 * Token验证类
	 * -3 - token验证失败
	 * -4 - token为空
	 */
	public function vaildToken()
	{
		//获取并验证Token
		$token = isset($_REQUEST['token'])?$_REQUEST['token']:'';
		$callback = isset($_REQUEST['callback'])?$_REQUEST['callback']:'';
		$flag = 0;
		if($token)
		{
		    $redisToken = $this->get('token');
			$this->del('token');
			if(!($redisToken==$token))
			{
				echo $callback . '(' .  json_encode(array('result'=>-3)) .')';exit;
			}
		
		}else
		{
			echo $callback . '(' .  json_encode(array('result'=>-4)) .')';exit;
		}
	}
	/**
	 * Token验证类
	 * -3 - token验证失败
	 * -4 - token为空
	 */
	public function vaildTokenNew()
	{
		//获取并验证Token
		$token = isset($_REQUEST['token'])?$_REQUEST['token']:'';
		$callback = isset($_REQUEST['callback'])?$_REQUEST['callback']:'';
		$flag = 0;
		if($token)
		{
			$redisToken = $this->get('token');
			$this->del('token');
			if(!($redisToken==$token))
			{
				echo $callback . '(' .  json_encode(array('state'=>3,'message'=>'令牌错误')) .')';exit;
			}
	
		}else
		{
			echo $callback . '(' .  json_encode(array('state'=>3,'message'=>'令牌错误')) .')';exit;
		}
	}
	
}
