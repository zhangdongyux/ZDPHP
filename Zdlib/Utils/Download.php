<?php
/**
 * 通过Nginx X-Accel-Redirect 进行文件下载
 * X-Accel-Redirect模式下载，会将文件直接推送客户端，不占用服务器物理内存
 * 
 * @author 张东宇
 * @version 1.0
 * @example
 * $f = new Utils_Download();
 * $f->getFile('测试下载文件.zip','xx.zip');
 *
 */
class Utils_Download {
	private $userAgent;	//HTTP_USER_AGENT
	private $filename; //下载文件名
	private $alias;		//Nginx目录映射别名
	private $limitRate = 1048576;	//限速下载
	
	/**
	 * 构造方法
	 */
	public function Utils_Download()
	{
		$this->userAgent = $_SERVER ["HTTP_USER_AGENT"];
	}
	
	/**
	 * 设置Nginx路径映射别名
	 * 
	 * @param string $alias	别名
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
	}
	
	/**
	 * 下载限速
	 * 
	 * @param int $limitRate	限速字节数Byte/s
	 */
	public function setLimitRate($limitRate)
	{
		$this->limitRate = $limitRate;
	}
	
	/**
	 * 下载文件
	 * 
	 * @param string $aliasname	下载文件名别名自定义
	 * @param string $realname	服务器文件真实名称
	 */
	function getFile($aliasname,$realname) {
		
		$encoded_filename = urlencode ( $aliasname );
		$encoded_filename = str_replace ( "+", "%20", $encoded_filename );
		
		header('Content-Type: application/octet-stream');
		header('X-Accel-Buffering: yes');
		header('X-Accel-Limit-Rate :'.$this->limitRate); //速度限制 Byte/s
		
		header ( 'Content-Type: application/octet-stream' );
		
		if (preg_match ( "/MSIE/", $this->userAgent )) {
			header ( 'Content-Disposition: attachment; filename="' . $encoded_filename . '"' );
		} else if (preg_match ( "/Firefox/", $this->userAgent )) {
			header ( 'Content-Disposition: attachment; filename*="utf8\'\'' . $aliasname . '"' );
		} else {
			header ( 'Content-Disposition: attachment; filename="' . $aliasname . '"' );
		}
		
		header('X-Accel-Redirect: /'.$this->alias."/$realname");
	}
}