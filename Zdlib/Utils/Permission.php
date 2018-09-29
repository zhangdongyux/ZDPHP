<?php
/**
 * 权限判定类
* @author 张东宇
* @version 1.0
* @example
 	$p = new Utils_Permission (); //创建权限类 
	//validate()方法使用
	echo $p->validate(10,2).'<br>';	//传入权值、权限码进行验证
	echo $p->validate (10,4, 'disabled' ).'<br>';//传入权值、权限码进行验证，返回显示/隐藏、可用/不可用
	echo $p->validate (10,8, '验证通过 ', '验证失败' ).'<br>';//自定义返回结果
	
	//validateJson方法使用
	 //每组权值
	$a = array('group1'=>10,'group2'=>14,'group3'=>8);
	//按钮权限码
	$b = array('group'=>'group1','code'=>4);
	//权值json串
	$ajson = json_encode($a);
	//权限码json串
	$bjson = json_encode($b);
	echo '<hr>';
	echo $p->validateJson($ajson,$bjson).'<br>';	//传入权值JSON、权限码JSON进行验证
	echo $p->validateJson ($ajson,$bjson, 'display' ).'<br>';//传入权值JSON、权限码JSON，返回显示/隐藏、可用/不可用
	echo $p->validateJson ($ajson,$bjson, '验证通过 ', '验证失败' ).'<br>';//传入权值JSON、权限码JSON，自定义返回结果
	
	//redirect方法使用
	$p->redirect(10, 2, 'http://www.baidu.com');
	
	//redirectJson方法使用
	$p->redirectJson($ajson, $bjson, 'http://www.sina.com');
*/
class Utils_Permission implements Utils_Interface_IPermission {
	private $authValue; // 权值
	private $code; // 权限码
	private $type; // 表单状态
	private $showMessage; // 自定义显示代码
	private $hiddenMessage; // 自定义隐藏代码
	
	/**
	 * 根据权值进行权限判定
	 *
	 * @param string $authValue  权值
	 * @param string $code	权限码     	
	 * @return string 0-没有权限 1-有权限
	 */
	private function validate2($authValue, $code) {
		$this->authValue = $authValue;
		$this->code = $code;
		return $this->checkValue ();
	}
	
	/**
	 * 根据权值进行权限判定，返回控制字符串，如（disabled="disabled"）
	 *
	 * @param string $authValue  权值
	 * @param string $code		 权限码 	
	 * @param string $type		 类型 disabled display
	 * @return string 			控制字符串
	 */
	private function validate3($authValue, $code, $type) {
		$this->authValue = $authValue;
		$this->code = $code;
		$this->type = $type;
		
		return $this->getToggle ();
	}
	/**
	 * 根据权值进行权限判定，并设置自定义显示/隐藏或者可用/禁用字符串
	 *
	 * @param string $authValue    权值    	
	 * @param string $showMessage   自定义显示/可用信息	
	 * @param string $hiddenMessage  自定义隐藏/禁用信息
	 * @return string
	 */
	private function validate4($authValue, $code, $showMessage, $hiddenMessage) {
		$this->authValue = $authValue;
		$this->code = $code;
		$this->showMessage = $showMessage;
		$this->hiddenMessage = $hiddenMessage;
		return $this->getMessage ();
	}
	/**
	 * 根据权值与权限码进行权限验证方法
	 * 吃
	 */
	public function validate() {
		$num = func_num_args ();
		if ($num < 2)
			return 'Err,Missing parameter';
		$name = "validate" . $num;
		$str = '';
		for($i = 0; $i < $num; $i ++) {
			if ($i == 0)
				$str .= func_get_arg ( $i )==''?0:func_get_arg ( $i );
			else
				$str .= (',' . func_get_arg ( $i ));
		}
		eval ( '$val = $this->' . $name . '(' . $str . ');' );
		return $val;
	}
	
	/**
	 * 根据权值JSON与权限JSON码进行权限验证方法
	 *
	 * @return string
	 */
	public function validateJson() {
		$num = func_num_args ();
		if ($num < 2)
			return 'Err,Missing parameter';
		
		$avjson = json_decode ( func_get_arg ( 0 ), true );
		$codeJson = json_decode ( func_get_arg ( 1 ), true );
		$code = $codeJson ['code'];
		$authValue = $avjson [$codeJson ['group']];
		
		$name = "validate" . $num;
		$str = '';
		for($i = 0; $i < $num; $i ++) {
			if ($i == 0)
				$str .= $authValue;
			else if ($i == 1)
				$str .= (',' . $code);
			else
				$str .= (',' . func_get_arg ( $i ));
		}
		eval ( '$val = $this->' . $name . '(' . $str . ');' );
		return $val;
	}
	
	/**
	 * 权限检查方法，带实现
	 *
	 * @return number 非0-有权限 0-无权限
	 */
	private function checkValue() {
		return ($this->authValue & $this->code) == 0 ? 0 : 1;
	}
	/**
	 * 判断元素是否可用或者是否可显示
	 *
	 * @return string 空串('')-元素正常显示、display="none"-元素隐藏、disabled="disabled-元素不可用
	 */
	private function getToggle() {
		if ($this->checkValue ()) {
			return '';
		} else {
			switch ($this->type) {
				case 'display' :
					{
						return 'display="none"';
					}
				case 'disabled' :
					{
						return 'disabled="disabled"';
					}
				default :
					{
						return '';
					}
			}
		}
	}
	/**
	 * 根据权限返回自定义消息内容
	 *
	 * @return string 自定义内容串
	 */
	private function getMessage() {
		if ($this->checkValue ())
			return $this->showMessage;
		else
			return $this->hiddenMessage;
	}
	/**
	 * 根据权值与权限码进行权限验证方法，并重定向到指定页面
	 * 
	 * @param string $authValue	权值     	
	 * @param string $code		权限码
	 * @param string $url        url地址
	 */
	public function redirect($authValue, $code, $url) {
		$this->authValue = $authValue;
		$this->code = $code;
		if ($this->checkValue ()) {
		} else {
			header ( "Location: $url" );
			exit ();
		}
	}
	/**
	 * 根据权值Json与权限码Json进行权限验证方法，并重定向到指定页面
	 * @param string $authJson	权值JSON     	
	 * @param string $codeJson	权限码JSON
	 * @param string $url        	url地址
	 */
	public function redirectJson($authJson, $codeJson, $url) {
		$avjson = json_decode ( $authJson, true );
		$codeJson = json_decode ( $codeJson, true );
		$code = $codeJson ['code'];
		$authValue = $avjson [$codeJson ['group']];
		
		$this->authValue = $authValue;
		$this->code = $code;
		
		if ($this->checkValue ()) {
		} else {
			header ( "Location: $url" );
			exit ();
		}
	}
}
