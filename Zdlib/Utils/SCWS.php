<?php
/**
 * 某公司分词程序，基于SCWS 中文分词 封装
 * 
 * @package Utils
 * @author 张东宇
 * @example
 * 	$so = new Utils_SCWS();		//创建分词类实例
	$so->set_charset('UTF8');	//设置字符集（默认为UTF8）
	$so->set_ignore(',');		//忽略逗号
	$so->send_text("我是一个中国人,我会C++语言,我也有很多T恤衣服"); //设置欲分词内容
	$rs  = $so->get_words();//返回所有词
	print_r($rs);		//打印结果
 * 	
 *
 */
class Utils_SCWS
{
	private $handle;//	分词句柄

	public function Utils_SCWS()
	{
		$this->handle = scws_open();
		scws_set_charset($this->handle,'UTF8');
	}

	/**
	 * 根据 send_text 设定的文本内容，返回系统计算出来的最关键词汇列表
	 * 参数 limit 可选参数，返回的词的最大数量，缺省是 10
	 参数 attr 可选参数，是一系列词性组成的字符串，各词性之间以半角的逗号隔开，
	 这表示返回的词性必须在列表中，如果以~开头，则表示取反，词性必须不在列表中，缺省为NULL，返回全部词性，不过滤。
	 返回值 成功返回统计好的的词汇组成的数组，返回 false。返回的词汇包含的键值如下：
	 - word _string_ 词本身
	 - times _int_ 词在文本中出现的次数
	 - weight _float_ 该词计算后的权重
	 - attr _string_ 词性
	 * @param string $text	设定的文本内容
	 * @param integer $count
	 * @return boolean  成功返回统计好的的词汇组成的数组,否则返回 false
	 */
	public  function get_tops($text,$count=10,$attr)
	{
		return scws_get_tops($this->handle,$count,$attr);
	}

	/**
	 * 根据 send_text 设定的文本内容，返回一系列切好的词汇
	 *
	 *  返回值 成功返回切好的词汇组成的数组，若无更多词汇，返回 false。返回的词汇包含的键值如下：
	 - word _string_ 词本身
	 - idf _float_ 逆文本词频
	 - off _int_ 该词在原文本路的位置
	 - attr _string_ 词性
	 注意 每次切词后本函数应该循环调用，直到返回 false 为止，因为程序每次返回的词数是不确定的。
	 */
	public function get_result()
	{
		return scws_get_result($this->handle);
	}

	/**
	 * 设定分词返回结果时是否去除一些特殊的标点符号之类
	 * 参数 yes设定值，如果为 true 则结果中不返回标点符号，如果为 false 则会返回，缺省为 false。
	 * @param boolean $yes
	 * @return boolean  始终为 true
	 */
	public function set_ignore($yes=false)
	{
		scws_set_ignore($this->handle,$yes);
	}

	/**
	 * 关闭一个已打开的 scws 分词操作句柄
	 *
	 * @return true
	 */
	public function close()
	{
		return scws_close($this->handle);
	}
	/**
	 *  设定分词词典、规则集、欲分文本字符串的字符集
	 *  **参数 charset** 要新设定的字符集，目前只支持 utf8 和 gbk。（注：默认为 gbk，utf8不要写成utf-8）
	 * @param string $charset 字符集
	 * @return true
	 */
	public function set_charset($charset="utf8")
	{
		return scws_set_charset($this->handle,$charset);
	}

	/**
	 * 添加分词所用的词典，新加入的优先查找
	 *  参数 dict_path 词典的路径，可以是相对路径或完全路径。（遵循安全模式下的 open_basedir）
	 参数 mode 可选，表示加载的方式。其值有：
	 - SCWS_XDICT_TXT  表示要读取的词典文件是文本格式，可以和后2项结合用
	 - SCWS_XDICT_XDB  表示直接读取 xdb 文件（此为默认值）
	 - SCWS_XDICT_MEM  表示将 xdb 文件全部加载到内存中，以 XTree 结构存放，可用异或结合另外2个使用。
	 * @param string $dict_path 词典的路径
	 * @param string $mode	加载的方式
	 * @return 成功返回 true 失败返回 false
	 */
	public function add_dict($dict_path,$mode = SCWS_XDICT_XDB)
	{
		return scws_add_dict($this->handle,$dict_path,$mode);
	}

	/**
	 * 设定分词所用的词典并清除已存在的词典列表
	 * 参数 dict_path 词典的路径，可以是相对路径或完全路径。（遵循安全模式下的 open_basedir）
	 * 参数 mode 可选，表示加载的方式。参见 `add_dict`
	 *
	 * @param string $dict_path 词典路径
	 * @param string $mode		加载方式
	 * @return boolean 成功返回 true 失败返回 false
	 */
	public function set_dic($dict_path,$mode = SCWS_XDICT_XDB )
	{
		return cws_set_dic($this->handle,$dict_path,$mode = SCWS_XDICT_XDB);
	}

	/**
	 * 设定分词所用的新词识别规则集（用于人名、地名、数字时间年代等识别）
	 * 参数 rule_path 规则集的路径，可以是相对路径或完全路径。（遵循安全模式下的 open_basedir）
	 * 参数 mode 可选，表示加载的方式。参见 `add_dict`
	 *
	 * @param string $rule_path
	 * @return boolean 成功返回 true 失败返回 false
	 */
	public function set_rule($rule_path)
	{
		return cws_set_dic($this->handle,$rule_path);
	}

	/**
	 * 设定分词返回结果时是否复式分割，如“中国人”返回“中国＋人＋中国人”三个词
	 * 参数 mode 复合分词法的级别，缺省不复合分词。取值由下面几个常量异或组合（也可用 1-15 来表示）：
		- SCWS_MULTI_SHORT   (1)短词
		- SCWS_MULTI_DUALITY (2)二元（将相邻的2个单字组合成一个词）
		- SCWS_MULTI_ZMAIN   (4)重要单字
		- SCWS_MULTI_ZALL    (8)全部单字

	 * @param integer $mode 复合分词法的级别
	 * @return boolean true 始终为 true
	 */
	public function set_multi($mode)
	{
		return cws_set_multi($this->handle,$mode);
	}

	/**
	 * 设定是否将闲散文字自动以二字分词法聚合
	 * 参数 yes设定值，如果为 true 则结果中多个单字会自动按二分法聚分，如果为 false 则不处理，缺省为 false。
	 *
	 * @param boolean $yes 设定值
	 * @return boolean true  始终为 true
	 */
	public function set_duality($yes)
	{
		return cws_set_duality($this->handle,$yes);
	}

	/**
	 * 发送设定分词所要切割的文本
	 * 参数text要切分的文本的内容。
	 注意系统底层处理方式为对该文本增加一个引用，故不论多长的文本并不会造成内存浪费；
	 执行本函数时，若未加载任何词典和规则集，则会自动试图在 ini 指定的缺省目录下查找缺省字符集的词典和规则集。
	  
	 * @param string $text 要切分的文本的内容
	 * @return boolean 成功返回 true 失败返回 false
	 */
	public function send_text($text)
	{
		return scws_send_text($this->handle,$text);
	}

	/**
	 * 根据 send_text 设定的文本内容，返回系统中词性符合要求的关键词汇
	 * 参数 attr 是一系列词性组成的字符串，各词性之间以半角的逗号隔开，
	 这表示返回的词性必须在列表中，如果以~开头，则表示取反，词性必须不在列表中，若为空则返回全部词。
	 返回值 成功返回符合要求词汇组成的数组，返回 false。返回的词汇包含的键值参见 `scws_get_result`
	  
	 * @param string $attr 一系列词性组成的字符串,各词性之间以半角的逗号隔开
	 * @return 参见以上说明
	 */
	public function get_words($attr='')
	{
		return scws_get_words($this->handle,$attr);
	}

	/**
	 * 根据 send_text 设定的文本内容，返回系统中是否包括符合词性要求的关键词
	 * 参数 attr 是一系列词性组成的字符串，各词性之间以半角的逗号隔开，
	 这表示返回的词性必须在列表中，如果以~开头，则表示取反，词性必须不在列表中，若为空则返回全部词。
	  
	 * @param string $attr 一系列词性组成的字符串
	 * @return boolean 如果有则返回 true，没有就返回 false
	 */
	public function has_words($attr)
	{
		return scws_has_words($this->handle,$attr);
	}

	/**
	 * 返回 scws 版本号名称信息（字符串）
	 */
	public function version()
	{
		return scws_version();
	}

}