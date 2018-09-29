<?php
/**
 * 密码生成类
 * 
 * 如果需要可分别编写前后台验证规则
 * 
 * @author 张东宇
 * @version 1.0
 * @example
 * 	Password::make($password);
 * 	返回结果：
 * 	Array ( [salt] => r8lbCQS4ng [password] => a594b5830ca27ee621d1011794d61082e4ee8a00 )
 * 	salt:随机salt
 *  password:加密密码字符串
 *
 */
class Utils_Password
{
	public static function make($password)
	{
		return self::rule($password);
	}
	
	/**
	 * DB 密码验证
	 * @param string $password	用户输入密码
	 * @param string $dbPassword	DB中存储的密码加密串
	 * @param string $salt		种子
	 * @return boolean	true-验证成功 	false-验证失败
	 */
	public static function auth($password,$dbPassword,$salt)
	{
		return self::getSha1($password, $salt)==$dbPassword;
	}
	/**
	 * 加密算法
	 * @param string $password	密码
	 * @param string $salt		种子
	 * @return string	密码串
	 */
	private static function getSha1($password,$salt)
	{
		return sha1(md5(md5($password).$salt));
	}
	
	/**
	 * 前台加密算法
	 * @param string $password	密码
	 * @param string $salt		种子
	 * @return string	密码串
	 */
	private static function rule($password)
	{
		$salt = Utils_RandomKey::generate_r(10);
		return array("salt"=>$salt,"password" => self::getSha1($password, $salt));
	} 
	
}