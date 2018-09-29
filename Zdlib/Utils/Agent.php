<?php
/**
 * 获取设备、浏览器&版本信息
 * 
 * @author 张东宇
 * @version 1.0
 */
class Utils_Agent {
	private $agent = false;	//agent 信息
	private $os = false;	//os 信息
	private $browser = '';	//浏览器信息
	private $browser_ver = ''; //浏览器版本
	
	public function __construct() {
		$this->agent = $_SERVER ['HTTP_USER_AGENT'];
	}
	
	/**
	 * 获取完整Agent信息
	 * @return boolean
	 */
	public function getAgent()
	{
		return $this->agent;
	}
	
	/**
	 * 获取浏览器信息
	 * @return string
	 */
	public function getBrowser() {
	    if (preg_match ( '/baidubrowser\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
	        $this->browser = 'Baidu';
	        $this->browser_ver = $regs [2];
	    }else
        if (preg_match ( '/baiduboxapp\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
            $this->browser = 'BaiduApp';
            $this->browser_ver = $regs [2];
        }else
		if (preg_match ( '/MicroMessenger\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = 'MicroMessenger';
			$this->browser_ver = $regs [2];
		}else
		if (preg_match ( '/MQQBrowser\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = 'QQ';
			$this->browser_ver = $regs [2];
		} else
		if (preg_match ( '/UCBrowser\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = 'UCBrowser';
			$this->browser_ver = $regs [2];
		}else
		if (preg_match ( '/OmniWeb\/(v*)([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = 'OmniWeb';
			$this->browser_ver = $regs [2];
		} else 

		if (preg_match ( '/Netscape([\d]*)\/([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'Netscape';
			$this->browser_ver = $regs [2];
		} else 

		if (preg_match ( '/Chrome\/([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'Chrome';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/safari\/([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'Safari';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/MSIE\s([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = 'Internet Explorer';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/Opera[\s|\/]([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'Opera';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/NetCaptor\s([^\s|;]+)/i', $this->agent, $regs )) {
			$this->browser = '(Internet Explorer ' . $this->browser_ver . ') NetCaptor';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/Maxthon/i', $this->agent, $regs )) {
			$this->browser = '(Internet Explorer ' . $this->browser_ver . ') Maxthon';
			$this->browser_ver = '';
		} else if (preg_match ( '/FireFox\/([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'FireFox';
			$this->browser_ver = $regs [1];
		} else 

		if (preg_match ( '/Lynx\/([^\s]+)/i', $this->agent, $regs )) {
			$this->browser = 'Lynx';
			$this->browser_ver = $regs [1];
		}
		
		if ($this->browser != '') {
			return $this->browser . ' ' . $this->browser_ver;
		} else {
			return 'Unknow browser';
		}
	}
	/**
	 * 取得客户操作体系
	 *
	 * @access public
	 * @return string
	 */
	public function getOS() {
		$this->agent = $_SERVER ['HTTP_USER_AGENT'];
		$os = false;
		if(eregi('macintosh', $this->agent))
		{
			$os = 'Mac';
		}elseif(eregi('iPad', $this->agent))
		{
			$os = 'iPad';
		}elseif(eregi('iPod', $this->agent))
		{
			$os = 'iPod';
		}elseif(eregi('iPhone', $this->agent))
		{
			$os = 'iPhone';
		}elseif(eregi('android', $this->agent))
		{
			$os = 'Android';
		}elseif(eregi ( 'win', $this->agent ) && strpos ( $this->agent, '95' )) {
			$os = 'Windows 95';
		} else if (eregi ( 'win 9x', $this->agent ) && strpos ( $this->agent, '4.90' )) {
			$os = 'Windows ME';
		} else if (eregi ( 'win', $this->agent ) && ereg ( '98', $this->agent )) {
			$os = 'Windows 98';
		} else if (eregi ( 'win', $this->agent ) && eregi ( 'nt 6.0', $this->agent )) {
			$os = 'Windows Vista';
		} else if (eregi ( 'win', $this->agent ) && eregi ( 'nt 6.1', $this->agent )) {
			$os = 'Windows 7';
		} else if (eregi ( 'win', $this->agent ) && eregi ( 'nt 5.1', $this->agent )) {
			$os = 'Windows XP';
		} else if (eregi ( 'win', $this->agent ) && eregi ( 'nt 5', $this->agent )) {
			$os = 'Windows 2000';
		} else if (eregi ( 'win', $this->agent ) && eregi ( 'nt', $this->agent )) {
			$os = 'Windows NT';
		} else if (eregi ( 'win', $this->agent ) && ereg ( '32', $this->agent )) {
			$os = 'Windows 32';
		} else if (eregi ( 'linux', $this->agent )) {
			$os = 'Linux';
		} else if (eregi ( 'unix', $this->agent )) {
			$os = 'Unix';
		} else if (eregi ( 'sun', $this->agent ) && eregi ( 'os', $this->agent )) {
			$os = 'SunOS';
		} else if (eregi ( 'ibm', $this->agent ) && eregi ( 'os', $this->agent )) {
			$os = 'IBM OS/2';
		} else if (eregi ( 'Mac', $this->agent ) && eregi ( 'PC', $this->agent )) {
			$os = 'Macintosh';
		} else if (eregi ( 'PowerPC', $this->agent )) {
			$os = 'PowerPC';
		} else if (eregi ( 'AIX', $this->agent )) {
			$os = 'AIX';
		} else if (eregi ( 'HPUX', $this->agent )) {
			$os = 'HPUX';
		} else if (eregi ( 'NetBSD', $this->agent )) {
			$os = 'NetBSD';
		} else if (eregi ( 'BSD', $this->agent )) {
			$os = 'BSD';
		} else if (ereg ( 'OSF1', $this->agent )) {
			$os = 'OSF1';
		} else if (ereg ( 'IRIX', $this->agent )) {
			$os = 'IRIX';
		} else if (eregi ( 'FreeBSD', $this->agent )) {
			$os = 'FreeBSD';
		} else if (eregi ( 'teleport', $this->agent )) {
			$os = 'teleport';
		} else if (eregi ( 'flashget', $this->agent )) {
			$os = 'flashget';
		} else if (eregi ( 'webzip', $this->agent )) {
			$os = 'webzip';
		} else if (eregi ( 'offline', $this->agent )) {
			$os = 'offline';
		} else {
			$os = 'Unknown';
		}
		return $os;
	}
}