<?php

/**
 * 
 * 系统日志类，基于Redis完成
 * 
 * @author 张东宇
 * @example
 * 			json格式说明
 * 		json_encode(array('tablename'=>'WXT_LOG_USER','ip'=>'127.0.0.1',......));
 * 		tablename为必填参数，其他参数采用键值对形式存放
 * 		key-数据库列
 * 		value-数据库列值
 * 
 *
 */
// class Utils_SystemLog_Log extends Utils_Interface_SQLTemplate implements Utils_Interface_ISystemLog
class Utils_SystemLog_Log implements Utils_Interface_ISystemLog
{
	private $redis;		//Redis实例
	private $prefix='WXT_LOG_';	//Redis日志Key前缀
	/**
	 * 1.覆盖超类的构造函数，构造函数的参数为存放sql语句文件的文件名
	 * 2.调用超类的构造函数，有两个参数：1.__CLASS__ 2,$sqlFile
	 *
	 */
// 	public function __construct($section="oracle",$sqlFile='sql_zh_cn.inc')
// 	{
// 		parent::__construct(__CLASS__,$section,$sqlFile,1);
// 		$this->redis = Utils_Redis::getInstance()->getRedis();
// 	}

	public function __construct()
	{
		$this->redis = Utils_Redis::getInstance()->getRedis();
	}
	
	
	/* (non-PHPdoc)
	 * @see Utils_Interface_ISystemLog::log()
	 */public function  log($model, $json) {
			$this->redis->set($this->prefix.$model.':'.Utils_RandomKey::generate_r(),$json);
		}

	/* (non-PHPdoc)
	 * @see Utils_Interface_ISystemLog::logToDB()
	 */public function logToDB() {
	 
	 	$keys = $this->redis->keys($this->prefix.'*'); //获取Keys
	 	
	 	print_r($keys);
	 	
	 	$this->redis->get();
// 		$this->dao->execute();
		
		}

	
}