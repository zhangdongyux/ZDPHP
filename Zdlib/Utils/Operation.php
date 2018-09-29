<?php
/**
 * 操作控制类
 * 
 * @author 张东宇
 * @version V1.0
 * @example
	$instance = Utils_Operation::getInstance();//获取类实例
	$ckey = $instance->encode(111,'product_id');	//获取key
	$instance->isRedirect(true);			//验证失败是否跳转，默认为false
	$instance->setUrl("http://www.baidu.com");//指定验证失败跳转地址
	
	$instance->vaildata("2ec40168bb312fd203c76fe96db36143",111,'product_id');//验证Key
 */
class Utils_Operation
{
	private $key = 'XX2.0L';				//操作Key标识
	private $url = 'http://www.xxx.com';	//跳转URL
	private $redirect = false;				//默认重定向URL
	
	private static $instance;				//类实例
	private function Utils_Operation(){}	//构造函数
	
	/**
	 * 获取类实例
	 * 
	 * @return Common_Operation	
	 */
	public static function getInstance()
	{
		if(!self::$instance)
			return new Utils_Operation();
		else
			return $self::$instance;
	}
	/**
	 * key加密算法实现
	 * 
	 * @param string $userId	用户ID
	 * @param string $beanId	产品、图片、证书等实体标识
	 * @param string $userStr	自定义其他加密参数项
	 * @return string	加密后Key值
	 */
	public function encode($userId='',$beanId='',$userStr='')
	{
		return md5(sha1($userId.$beanId.$userStr).$this->key);
	}
	
	/**
	 * 验证Key
	 * 
	 * @param string $key		用户请求Key
	 * @param string $userId	用户ID
	 * @param string $beanId	产品、图片、证书等实体标识
	 * @param string $userStr 	自定义其他加密参数项
	 * @return boolean true验证成功 false验证失败 如果设置跳转URL并开启跳转功能，将跳转指定URL
	 */
	public function vaildata($key,$userId,$beanId='',$userStr='')
	{
		$keyen = $this->encode($userId,$beanId,$userStr);
		if (!($key == $keyen))
		{
			if($this->redirect)
			{
// 				   echo 'Jump';
				header("location:$this->url");
			}
			else 
			{
				return false;
			}
		}
		return true;
	}
	/**
	 * 设置验证失败跳转URL
	 * 
	 * @param string $url 跳转URL
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}
	
	/**
	 * 获取URL地址
	 * 
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * 获取URL地址
	 *
	 * @return string
	 */
	public function isRedirect($flag = true)
	{
		$this->redirect = $flag;
	}
}