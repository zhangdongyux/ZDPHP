<?php
/**
 * Rest抽象接口
 */
abstract  class Rest_Interface {
// 	private $_allow = array ('COM'=>'aVsAQz7M');//企业密钥
	private $_content_type = "application/json"; // 数据格式
	private $_request = array (); // 请求数组
	private $_method = "";	//调用方法
	private $_code = 200;	//http请求状态码
	public function Rest_Interface() {
		$this->inputs ();
// 		print_r($this->_request);
	}
	public function get_referer() {	//获取来源地址
		return $_SERVER ['HTTP_REFERER'];
	}
	public function response($data, $status) {	//服务器响应
		$this->_code = ($status) ? $status : 200;	//默认200
		$this->set_headers ();	//设置响应头信息
		echo $data;				//响应输出
		exit ();
	}
	/**
	 * 获取状态码信息
	 * @return string 状态码
	 */
	private function get_status_message() {
		$status = array (
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => '(Unused)',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported' 
		);
		return ($status [$this->_code]) ? $status [$this->_code] : $status [500];//状态码不再列表中返回500
	}
	/**
	 * 获取查询方法
	 * @return string POST GET PUT DELETE
	 */
	public function get_request_method() {
		return $_SERVER ['REQUEST_METHOD'];
	}
	/**
	 * 请求数据封装
	 */
	private function inputs() {
		switch ($this->get_request_method ()) {
			case "POST" :
				$this->_request = $this->cleanInputs ( $_POST );
				break;
			case "GET" :
			case "DELETE" :
				$this->_request = $this->cleanInputs ( $_GET );
				break;
			case "PUT" :
				parse_str ( file_get_contents ( "php://input" ), $this->_request );
				$this->_request = $this->cleanInputs ( $this->_request );
				break;
			default :
				$this->response ( '', 406 );
				break;
		}
	}
	/**
	 * 请求数据封装
	 * @param string|array $data	根据请求类型为string或者array
	 * @return string|array
	 */
	private function cleanInputs($data) {
		$clean_input = array ();
		if (is_array ( $data )) {
			foreach ( $data as $k => $v ) {
				$clean_input [$k] = $this->cleanInputs ( $v );
			}
		} else {
			$data = strip_tags ( $data );
			$clean_input = trim ( $data );
		}
		return $clean_input;
	}
	
	/**
	 * 获取Request
	 * @return multitype:
	 */
	public function getRequest()
	{
		return $this->_request;
	}
	
	/**
	 * 处理API
	 * 错误代码：
	 * *****************************
	 * -1 接口方法不存在 返回404状态码
	 * 0  密钥不正确  返回406状态码
	 * 1 加密串验证不正确 返回406状态码
	 * *****************************
	 */
	public function processApi(){
		$func = strtolower(trim(str_replace("/","",$_REQUEST['request'])));
// 		if(!in_array($this->_request['key'], $this->_allow))
// 			$this->response(json_encode(array('err'=>'0')), 406);
//         $this->sign();
		if(!isset($this->_request['sign']) || $this->_request['sign']!=$this->sign())
			$this->response(json_encode(array('err'=>'-1','msg'=>'Signature verification failed.')), 406);
		elseif((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response(json_encode(array('err'=>'-2','msg'=>'The request method does not exist.')),404);// If the method not exist with in this class, response would be "Page not found".
	}
	
	/**
	 * 签名验证
	 * 签名字段不包含sign与file字段
	 * @param $comkey 企业密钥
	 * @return string
	 */
	private function sign($comkey='')
	{
		$code='';
		foreach ($this->_request as $key=>$value)
		{
			if($key=='sign'||$key=='file')
				continue;	
			$code .= $key.$value;
		}
// 		echo '====='.md5(sha1($code).$comkey).'=====';
		return md5(sha1($code).$comkey);
	}
	
	/**
	 * 设置头信息
	 */
	private function set_headers() {
		header ( "HTTP/1.1 " . $this->_code . " " . $this->get_status_message () );
		header ( "Content-Type:" . $this->_content_type );
	}
}
