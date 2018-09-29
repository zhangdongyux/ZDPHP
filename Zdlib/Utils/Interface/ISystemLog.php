<?php

/**
 * 
 * 系统日志类，基于Redis完成
 * 
 * @author 张东宇
 *
 */
interface Utils_Interface_ISystemLog
{
	public function log($model,$json);
	public function logToDB();
}