<?
require_once('autoload.inc');
/**
 * 国际化操作类
 * 
 * 此类负责解析Lang/Module/XX模块下的语言文件（zh_cn...)
 * 
 * @package Utils
 * @access public
 * @author 张东宇
 * @version 1.0
 */
class Utils_I18n
{

	private $lang="zh_cn";
	private $properties;
	private $isprocess; //是否进行片段提取
	private $langpath;
	/**
	 * 构造函数
	 *
	 * @param string $moduleName	需要解析的具体模块
	 */
	function __construct($moduleName,$langValue='')
	{
		if($langValue=='')
	 		$lang=$this->getCookies();
	 	else 
	 		$lang=$langValue;
		if($lang)
			$this->lang=$lang;
		$this->langpath="";
		if(file_exists(WebLibPath::path().'Lang/'.$moduleName.'/'.$this->lang.'.inc'))
			$this->langpath='Lang/'.$moduleName.'/'.$this->lang.'.inc';
		else 
			$this->langpath='Lang/'.$moduleName.'/zh_cn.inc';
		$this->properties=parse_ini_file($this->langpath,false);
	}
	
	/**
	 * 获取语言文件的具体数据
	 *
	 * @return array 返回一个语言文件中所有值的一维数组
	 */
	function getProperties()
	{
		return $this->properties;
	}
	/**
	 * 获取Cookies
	 * 
	 * @return string 用于标识当前web语言的字符串
	 */
	function getCookies()
	{
		
		return $_COOKIE['ZDLANG'];
	}
	
}