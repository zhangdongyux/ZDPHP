<?
/**
 * 确定国际化文件路径前缀
 * 
 * 通过静态方法path()得到类路径,提供给SQLTemplate使用
 * 
 * @package Lang
 * @access public
 * @author 张东宇
 * @version 1.0
 */
class WebLibPath
{
	/**
	 * 获取国际化文件路径前缀
	 *
	 * @static
	 * @return string 国际化文件路径前缀
	 */
	public static function  path()
	{
		return substr(__FILE__,0,-14);
	}
}