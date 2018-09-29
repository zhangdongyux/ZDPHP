<?php
/**
 * 分页接口
 * 本接口未提供任何setters方法，如需使用请自行修改此接口
 *
 * @author 张东宇
 * @version 2.0
 * @copyright http://swengineer.iteye.com 2012
 * 
 * @example
 *    public function getHTML($currentPage)
    {
        $this->initCurrentPage($currentPage);
        header("Content-type: text/html; charset=utf-8"); 
        echo "起始页码:".$this->getStartPage().'<hr>';
        echo "结束页码:".$this->getEndPage().'<hr>';
        echo "总页数:".$this->getTotalPage().'<hr>';
        echo "当前页".$this->getCurrentPage().'<hr>';
        echo "上一页:".$this->getPreNextPage('up').'<hr>';
        echo "下一页:".$this->getPreNextPage('next').'<hr>';
        echo "BaseURL:".$this->getUrl();
//      print_r($this->getConfig());echo '<hr>';
    }
 *           
 */
abstract class Utils_Interface_IPagination {
	private $beanCount = 1;//实体总记录数
	private $showBeanCount = 1;//每页显示最多记录数
	private $totalPage = 1; // 总页数
	private $currentPage = 1; // 当前页
	private $showCount = 1; // 页面显示最大页数
	private $url = ''; // 页面URL
	protected $config = array (); // 用于页面输出调试使用，不做核心逻辑处理（显示层）
	private $startPage = 1; // 开始页码
	private $endPage = 1; // 结束页码
	
	/**
	 *
	 * @param string $totalPage
	 *        	总页数
	 * @param string $config
	 *        	分页配置参数，配置项如下：
	 *        	array(
	 *        	'url'=>'http://xx.xxxx.com/list', //生成分页URL使用的base URL,此URL在Action拼装，使用$_GET即可
	 *        	'beanCount'=>$dao->getUserTotalPage(),//需要分页结果集总大小，根据此大小与每页显示多少showBeanCount来确定分页页数
	 *        	'showBeanCount'=>10,//每页显示的记录数
	 *        	'showCount'=>3	//分页区域最多显示多少页码
	 *        	)
	 */
	public function __construct($config=array(),$totalPage = 1) {
		
		$this->config = $config;
		//如果配置中传入实体总数beanCount，构造函数的$totalPage将不起作用,大部分使用此情况
		$this->beanCount = isset($config ['beanCount'])?$config ['beanCount']:$this->beanCount;
		$this->showCount = isset($config ['showCount'])?$config ['showCount']:$this->showCount;
		$this->showBeanCount = isset($config ['showBeanCount'])?$config ['showBeanCount']:$this->showBeanCount;
		$this->totalPage = isset($config ['beanCount'])?ceil($config ['beanCount']/$this->showBeanCount):$totalPage;
		$this->url = isset($config ['url'])?$config ['url']:$this->url;
	}
	
	/**
	 * 
	 * @param string $currentPage 初始化相关参数
	 */
	protected function initCurrentPage($currentPage) {
		
		$currentPage = intval($currentPage);
		if($currentPage > $this->totalPage)
			$this->currentPage = $this->totalPage;
		elseif ($currentPage < 1)
			$this->currentPage=1;
		else 
			$this->currentPage = $currentPage;

		
		$mod = $this->currentPage % $this->showCount; // //当前页%页面显示最大页数（取余数确定页面显示页码范围）
		$cod = intval ( $this->currentPage / $this->showCount ); // 当前页/页面显示最大页数（取整数确定页面显示页码范围
		$currentShowNumArea = ! $mod ? $cod : $cod + 1; // 当前显示的分页区域(1区，2区)
		if(!$currentShowNumArea)
		{
			$this->startPage=1;
			$this->endPage=1;
		}else
		{
			$this->startPage = $currentShowNumArea * $this->showCount - ($this->showCount - 1); // 开始页码
			$this->endPage = ($currentShowNumArea * $this->showCount) > ($this->totalPage) ? ($this->totalPage) : ($currentShowNumArea * $this->showCount); // 结束页码
		}	
		
// 		echo $this->currentPage;
	}
	
	/**
	 * 获取上一页/下一页页码
	 *
	 * @param string $flag
	 *        	上一页/下一页标识 up-上一页 其他为下一页
	 */
	protected function getPreNextPage($flag = 'up') {
		$resultPage = 1;
		if ($flag == 'up')
			$resultPage = $this->currentPage - 1 < 1 ? 1 : $this->currentPage - 1;
		else
			$resultPage = $this->currentPage + 1 > $this->totalPage ? $this->totalPage : $this->currentPage + 1;
		return $resultPage;
	}
	/**
	 * 获取分页HTML代码(此部分需要将静态页中分页HTML剥离开，通过此函数控制输出)
	 * Call $this->initCurrentPage($currentPage=1) first;
	 * 
	 * 分页函数主要利用一下参数变量
	 * 
	 * $totalPage 	总页数
	 * $startPage 	开始页
	 * $end			结束页
	 * $current		当前页
	 * $url			分页BaseURL
	 * 
	 */
	abstract function getHTML($currentPage);
	
	/**
	 * 获取配置参数信息数组
	 */
	protected function getConfig() {
		return $this->config;
	}
	
	/**
	 * @return the $totalPage
	 */
	public function getTotalPage() {
		return $this->totalPage;
	}
	
	/**
	 * @return the $currentPage
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}
	
	/**
	 * @return the $showCount
	 */
	public function getShowCount() {
		return $this->showCount;
	}
	
	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @return the $startPage
	 */
	public function getStartPage() {
		return $this->startPage;
	}
	
	/**
	 * @return the $endPage
	 */
	public function getEndPage() {
		return $this->endPage;
	}
}