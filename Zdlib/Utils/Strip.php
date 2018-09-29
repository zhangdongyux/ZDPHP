<?php
/**
 * html字符串过滤类,避免XSS攻击
 * 
 * @author 张东宇
 * @version V1.0
 * @example
 * $str = "javascript:alert(\"123'<html>$%^&*()_=-+);";
   $str1 = 'text" value="444';
   echo Utils_Strip::html_string($str).'<br>';
   echo Utils_Strip::html_string($str1).'<br>';
 */
class Utils_Strip {
	/**
	 * HTML字符转义，过滤Javascript以及HTML字符
	 * 
	 * @param string $string	欲转义字符串
	 * @return string	转换后字符串
	 */
	public static function html_string($string) {
		$pattern [0] = '/\&/';
		$pattern [1] = '/</';
		$pattern [2] = "/>/";
		$pattern [3] = '/\n/';
		$pattern [4] = '/"/';
		$pattern [5] = "/'/";
		$pattern [6] = "/%/";
		$pattern [7] = '/\(/';
		$pattern [8] = '/\)/';
		$pattern [9] = '/\+/';
		$pattern [10] = '/-/';
		$replacement [0] = '&amp;';
		$replacement [1] = '&lt;';
		$replacement [2] = '&gt;';
		$replacement [3] = '<br>';
		$replacement [4] = '&quot;';
		$replacement [5] = '&#39;';
		$replacement [6] = '&#37;';
		$replacement [7] = '&#40;';
		$replacement [8] = '&#41;';
		$replacement [9] = '&#43;';
		$replacement [10] = '&#45;';
		return preg_replace ( $pattern, $replacement, $string );
	}
	
	/**
	 * \r\n替换为html中<br>标签
	 * 
	 * @param string $str 原始字符串
	 * @return 替换后字符串
	 */
	public static function brReplace($str)
	{
		return preg_replace("/[\r\n]+/","<br>",$str);
	}
	
	public static function get($param)
	{
		return htmlspecialchars($_GET["$param"]);
	}
	
	public static function post($param)
	{
	    return htmlspecialchars($_POST["$param"]);
	}
	
	/**
	 * 参数转义处理，防止sql注入 参考Java PreparedStatement 对象的 setString 实现
	 * @param string $string	原始字符串
	 * @param type $type		处理类型 0-字符串 1-数字 默认0
	 * @return number|string
	 */
	function prepare($string,$type=0) {
		if(!$type)
			return (int)$string;
		$str = '';
		for ($i = 0; $i < strlen($string); ++$i)
		{
			$c = substr($string, $i,1);
			if ($c == '\\' || $c == '\'' || $c == '\"')
				$str.='\\';
			$str.=$c;
		}
		return $str;
	}
}