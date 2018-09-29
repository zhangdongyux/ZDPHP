<?
/**
 * 自定义异常类
 * 
 * 用户抛出各种异常
 * 
 * @package Exception
 * @access public
 * @abstract 抽象类
 * @author 张东宇
 * @version 1.0
 */

class Exception_CEException extends Exception
{
	/**
	 * 构造函数
	 * 
	 * 重定义构造器使 message 变为必须被指定的属性
	 */ 
	function __construct($message, $code = 0)
	{
		// 确保所有变量都被正确赋值
		parent::__construct($message, $code);
	}
	/**
	 * 重载toString
	 *
	 * 重载toString 自定错误输出格式
	 * @return string 错误消息
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->getCode()}]: {$this->getMessage()}<br>";
	}
}