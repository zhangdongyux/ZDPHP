<?php
/**
 * 敏感词过滤程序
 * 
 * 注意：本类为为实现敏感词库提取，请根据具体业务逻辑自行实现
 * 词库来源可由：数据库、缓存、文件系统、接口等获取
 * @version 1.0
 * @author 张东宇
 * @example
 *  $aWord = Utils_AllergicWord::getInstance();	//获取敏感词操作类实例
	$aWord->setAllergicWords(array('脏话','骂人','万选通'));	//设置词库，数组形式
	print_r($aWord->isAllergicWithWords('这句话包含了脏话和骂人话'));//调用内容是否存在敏感词
	echo $aWord->isAllergic('这句话包含了脏话和骂人话');//调用内容是否存在敏感词
 */
class Utils_AllergicWord
{
	private static $instance;	//类实例
	private $allergicWords;		//敏感词库
	
	private function __construct(){}//构造方法
	
	/**
	 * 获取敏感词过滤类实例
	 * @return Utils_AllergicWord
	 */
	public static function getInstance()
	{
		if(isset(self::$instance))
			return self::$instance;
		else
			return new Utils_AllergicWord();
	}
	/**
	 * 设置敏感词库
	 * @param array $allergicWords 敏感词库
	 */
	public function setAllergicWords($allergicWords=array())
	{
		$this->allergicWords = $allergicWords;
	}
	
	/**
	 * 	敏感词判断方法
	 * 
	 * @param string $keyword 原文本
	 * @return number|boolean	如果敏感词库未设置，返回-1 包换敏感词返回 true 否则返回 false
	 */
	public function isAllergic($keyword='')
	{
		if(!is_array($this->allergicWords)||empty($this->allergicWords))
			return -1;
// 		$json = json_encode(array('脏话','骂人话'));
		$count=0;
		$allergicWordCount = count($this->allergicWords);
		for ($i=0;$i<$allergicWordCount;$i++){
			$count = substr_count($keyword, $this->allergicWords[$i]);
			if($count>0)
				break;
		}
		return $count>0?true:false;
	}
	
	/**
	 * 敏感词判断方法，返回相关敏感词
	 * 
	 * @param string $keyword 原文本
	 * @return number|boolean
	 */
	public function isAllergicWithWords($keyword='')
	{
		if(!is_array($this->allergicWords)||empty($this->allergicWords))
			return -1;
		// 		$json = json_encode(array('脏话','骂人话'));
		$words = array();
		$allergicWordCount = count($this->allergicWords);
		for ($i=0;$i<$allergicWordCount;$i++){
			$count = substr_count($keyword, $this->allergicWords[$i]);
			if($count>0)
				$words[] = $this->allergicWords[$i];
		}
		return $words;
	}
}