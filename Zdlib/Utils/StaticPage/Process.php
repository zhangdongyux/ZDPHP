<?php
/**
 * 生成静态文件类
 * 
 * 说明：生成静态页面类
 * @author：张东宇
 * @version v1.0
 * @example
 * 
 * 		$isStatic = false; //本页相关超链接是否生成静态页面
 * 		echo Utils_StaticPage_Process::staticPage('AA-22-22-22.html','test.php','LinkText',$isStatic); //输出超链接
 * 
 */

class Utils_StaticPage_Process {
	
	private static $instance;			//类实例
	private $isStatic = true;	//是否生成静态页面
	
	private function Utils_StaticPage_Process(){
	}
	
	public static function getInstance()
	{
		if(self::$instance)
			return self::$instance;
		else
			return new Utils_StaticPage_Process();
	}
	
	/**
	 * 控制是否生成静态页面
	 * @param boolean $isStatic ture 生成 false 不生成
	 */
	public function setIsStatic($isStatic = true)
	{
		$this->isStatic = $isStatic;
	}
	
	/**
	 * 静态话页面超链接
	 * 
	 * @param string $hrefStatic	静态页面地址
	 * @param string $href			原始PHP地址
	 * @return string				超链接地址
	 */
	public function link($hrefStatic,$href)
	{
		if($this->isStatic)
			return $hrefStatic;
		return $href;
	}
	
	/**
	 * 静态页面请求存入Redis
	 *
	 * @param string $key	RedisKey
	 * @param string $url	生成网站的URL
	 * @param string $file  生成文件路径信息
	 */
	public function page($key,$file )
	{
		if(!$this->isStatic)
			return;
		$childFloder = 'static/';
		if($_GET['wstp']=='del')
			return;
		$url =  "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"].'&wstp=del';
		$prefix = "staticpage_wxt20_";
		$redisValue = array('url'=>$url,'file'=>$childFloder.$file);
		$redisValueJson = json_encode($redisValue);
		$redis = Utils_Redis::getInstance()->getRedis();
		$redis->set($prefix.$key,$redisValueJson);
		$redis->close();
	}
	
	/**
	 * 生成静态文件
	 * 此方法不建议使用
	 * 
	 * @param string $url				解析URL
	 * @param string $floder			静态文件目录
	 * @param string $staticFileName	文件名
	 * @param string $charset			编码
	 */
	
	public  function generate($url = '', $floder = '', $staticFileName, $charset = 'UTF-8') {
		if (! is_dir ( $floder )) {
			mkdir ( $floder, 777, true );
		}
		$httpx = substr($url,0,5);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		if($httpx=='https')
		{
			curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  2);
		}
		
		$content = curl_exec($curl); 
		curl_close($curl); 
//		echo $data;
//		exit;
		
//		$content = iconv ( '', $charset, $content );
//		echo $floder;
//		exit;
		file_put_contents ( $floder . DIRECTORY_SEPARATOR . $staticFileName, $content );
	}
}