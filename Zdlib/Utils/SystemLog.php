<?php

/**
 * 
 * 系统日志类，基于Redis完成
 * 
 * @author 张东宇
 *
 */
class Utils_SystemLog extends Utils_Interface_SQLTemplate implements Utils_Interface_ISystemLog 
{
	/**
	 * 1.覆盖超类的构造函数，构造函数的参数为存放sql语句文件的文件名
	 * 2.调用超类的构造函数，有两个参数：1.__CLASS__ 2,$sqlFile
	 *
	 */
	public function __construct($section="oracle",$sqlFile='sql_zh_cn.inc')
	{
		parent::__construct(__CLASS__,$section,$sqlFile,1);
	}
	
	private $redis;
	/* (non-PHPdoc)
	 * @see Utils_Interface_ISystemLog::log()
	 */public function  log($model, $json) {
			$redis = Utils_Redis::getInstance()->getRedis();
			$redis->set($model.'_'.Utils_RandomKey::generate_r(),$json);
		}

	/* (non-PHPdoc)
	 * @see Utils_Interface_ISystemLog::logToDB()
	 */public function logToDB() {
		// TODO Auto-generated method stub
		}

	
}