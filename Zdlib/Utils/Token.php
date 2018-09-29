<?php
/**
 * Token
 * 
 * 令牌类，避免CSFR/XSFR、会话劫持、固定攻击、表单伪造攻击等。
 * 使用方法：
 * 1.提交页面获取Token，并赋值到隐藏域中，获取Token代码如下：
 * 		$token_value = Utils_Token::getInstance()->gen_token();
 * 2.提交后在Action页面验证Token，根据返回值进行相应页面处理，验证代码如下：
 * 		$isValid = Utils_Token::getInstance()->validate($token_value);
 * 3.验证失败也可直接指定跳转URL如：
 * 		Utils_Token::getInstance()->validate($token_value,'http://xx.xxx.com')
 * 
 * @package Utils
 * @author 张东宇
 * @version 1.0
 * @example
 * 		$token_value = Utils_Token::getInstance()->gen_token(); //获取Token
 *		echo 'TOKEN_VALUE:'.$token_value.'<br>';				//打印Token
 *		$checkValue = Utils_Token::getInstance()->validate($token_value);//验证令牌
 *		echo $checkValue;//验证结果：1-成功 0-失败
 * 
 */
class Utils_Token {
	const TOKEN_NAME = 'ZOL_TOKEN'; // 令牌名
	private static $instance; // 类实例
	private $isCookie = 0; //是否使用cookie
	private function __construct($isCookie = 0) { // 构造函数
		if(!$isCookie)
		{
			if (! isset ( $_SESSION ))
				session_start ();
		}else 
		{
			$this->isCookie = 1;
		}
	}
	/**
	 * 获取令牌类实例
	 *
	 * @access public
	 * @return Token Utils_Token 实例
	 */
	public static function getInstance($isCookie = 0) {
		if (! isset ( self::$instance ))
			return new Utils_Token ($isCookie);
		else
			return self::$instance;
	}
	
	/**
	 * 生成令牌,供表单或其他业务逻辑使用
	 *
	 * @access public
	 * @return string 令牌值
	 */
	public function gen_token() {
		$hash = md5 ( uniqid ( rand () . 'ZOL', true ) );
		$n = rand ( 0, 24 );
		$token = substr ( $hash, $n, 8 );
		$this->_tokenValue = $token;
		if($this->isCookie)
		{
			setcookie(self::TOKEN_NAME, $this->_tokenValue, time()+3600);
		}else {
			$this->destroy_token ();
			$_SESSION [self::TOKEN_NAME] = $this->_tokenValue;
		}
		return $token;
	}
	
	/**
	 * 销毁令牌
	 *
	 * @access private
	 * @return void
	 */
	private function destroy_token() {
		if ($_SESSION [self::TOKEN_NAME])
			unset ( $_SESSION [self::TOKEN_NAME] );
	}
	
	/**
	 * 检测令牌是否有效
	 *
	 * @access public
	 * @param string $token
	 *        	令牌值
	 * @param string $url
	 *        	校验失败跳转URL
	 * @return boolean 1-校验成功 0-校验失败
     *
	 */
	public function validate($token, $url = '') {
		if($this->isCookie)
		{
			setcookie(self::TOKEN_NAME);
			if($token&&$token == $_COOKIE [self::TOKEN_NAME])
				return $token;
		}else
		if ($token == $_SESSION [self::TOKEN_NAME] && $token) {
			$this->destroy_token ();
			return $token;
		} elseif ($url) {
			if($this->isCookie)
				setcookie(self::TOKEN_NAME);
			else
				$this->destroy_token ();
			Header ( "Location: $url" );
		} else {
			if($this->isCookie)
				setcookie(self::TOKEN_NAME);
			else 
				$this->destroy_token ();
			return 0;	
		}
	}
}
/*
$token_value = Utils_Token::getInstance()->gen_token(); //获取Token
echo 'TOKEN_VALUE:'.$token_value.'<br>';				//打印Token
$checkValue = Utils_Token::getInstance()->validate($token_value);//验证令牌
echo $checkValue;//验证结果：1-成功 0-失败
*/