<?php
/**
 * 字符集转换类
 * 
 * GBK->UTF8转换
 * UTF8->GBK转换
 * 
 * @package Utils
 * @access public
 * @author 张东宇
 * @version 1.0
 */
class Utils_Charset
{
	/**
	 * GBK->UTF8转换
	 *
	 * @param string $str 转换字符串
	 * @return string
	 */
	public static function GBK2UTF8($str='')
	{
		return iconv('GBK','UTF-8',$str);
	}
	
	/**
	 * UTF8->GBK转换
	 *
	 * @param string $str 转换字符串
	 * @return string
	 */
	public static function UTF82GBK($str='')
	{
		return iconv('UTF-8','GBK',$str);
	}
	
	public static function returnRs($result='',$htmlCharset='utf8',$dbCharset='gbk')
	{
		 mb_convert_variables($htmlCharset,$dbCharset,$result);
		return $result;
	}
}
?>