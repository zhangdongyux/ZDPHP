<?php
/**
 * Redis初始化类
 * 
 * 提供Redis具体实例，供程序代码中使用
 * 
 * @package Utils
 * @author 张东宇
 * @version 1.0
 * @example
 * 
 * 	$redis = Utils_Redis::getInstance()->getRedis(); //获取Redis句柄
	$redis->set('key', 'val');	//设置值
	echo $redis->get('key');	//获取值
	//其他相关操作请参见Redis参考手册：http://www.redis.io/commands
 */
class Utils_Redis {
	private $host = '127.0.0.1';
	private $port = '6379';
	private static $instance = null;
	private $redis = null;
	
	/**
	 * 构造函数
	 */
	private function Utils_Redis() {
	}
	
	/**
	 * 获取Utils_Redis实例
	 *
	 * @return Utils_Redis Utils_Redis 实例
	 */
	public static function getInstance() {
		if (self::$instance) {
			return $this->instance;
		} else {
			return new Utils_Redis ();
		}
	}
	/**
	 * 获取Redis句柄
	 *
	 * @return Redis Redis实例
	 */
	public function getRedis() {
		$this->redis = new Redis ();
		$this->redis->connect ( $this->host, $this->port );
		return $this->redis;
	}
}