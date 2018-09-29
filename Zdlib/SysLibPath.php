<?
/**
 * 确定Zdlib绝对路径
 * 
 * 通过静态方法path()得到Zdlib绝对路径
 * 
 * @author 张东宇
 * @version 1.0
 */
class SysLibPath
{
	/**
	 * 获取Zdlib绝对路径
	 *
	 * @static
	 * @return string Zdlib绝对路径
	 */
	public static function  path()
	{
		return substr(__FILE__,0,-14);
	}
}