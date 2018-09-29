<?php
require_once 'Utils/Phpmailer/class.phpmailer.php';
class Utils_Phpmailer_SendMail
{	
	private $mail = '';
	private $charSet = 'utf-8';
	private $host = 'mail.zol.com.cn';
	private $port = '25';
	private $SMTPAuth = false; //一般情况为true
	private $from = 'service@zol.com.cn';
	private $formName = '中关村在线性能监控';
	private $userName = '';
	private $password = '';
	private $subject = '测试邮件';
	private $mime = 'text/html';
	private $body = '';
	private $isHTML = true;
	private $replyMail = '';
	private $replyName = '';
	private $sendAddress = array();
	
	function __construct()
	{
		$this->mail = new PHPMailer2();	// 为了避免与其他PHPMAILER重复，故加2以区别
		$this->mail->CharSet = $this->charSet;
		$this->mail->Host = $this->host;
		$this->mail->Port = $this->port;
		
		$this->mail->From = $this->from;
		$this->mail->FromName = $this->formName;
		$this->mail->SMTPAuth = $this->SMTPAuth;
		$this->mail->Username = $this->userName;
		$this->mail->Password = $this->password;
		$this->mail->Subject = $this->subject;
		$this->mail->AltBody = $this->mime;
		$this->setSMTPorMail('smtp');
		$this->mail->Body = $this->body;
		$this->mail->IsHTML($this->isHTML);
		$this->mail->AddReplyTo($this->replyMail,$this->replyName);
	}
	
	/**
	 * 添加收件人，多个的话，调用多次即可
	 * @param unknown $address
	 * @param string $addressname
	 */
	public function addAddress($address,$addressname='')
	{
	    $this->mail->AddAddress($address,$addressname==''?$address:$addressname);
	}
	
	/**
	 * 发送邮件
	 * @param unknown $address
	 * @param string $addressname
	 * @return boolean
	 */
	public function send()
	{
		if(!$this->mail->Send())
		{
			echo '失败';
			$this->mail->ClearAddresses();
	  		return false;
		}
	 	else {
			$this->mail->ClearAddresses();
	 		return true;
	 	}
		
	}
	/**
	 * 设置标题
	 *
	 * @param String $subject 邮件标题
	 */
	public function setSubject($subject)
	{
		$this->mail->Subject = $subject;
	}
	/**
	 * 设置文件主体内容
	 *
	 * @param String $body	邮件正文
	 */
	public function setBody($body)
	{
		$this->mail->Body = $body;
	}
	/**
	 * 设置采用SMTP方式发送邮件
	 */
	public function setSMTPorMail($type='smtp')
	{
		if($type=='smtp')
			$this->mail->IsSMTP();
		else
			$this->mail->IsMail();
	}
	
	/**
	 * 添加附件
	 * @param unknown $string
	 * @param unknown $filename
	 * @param string $encoding
	 * @param string $type
	 */
	public function addAttachment($path, $name = "", $encoding = "base64", $type = "application/octet-stream")
    {
           $this->mail->AddAttachment($path, $name,$encoding,$type);                          
    }
    
    /**
     * 添加字符流到附件而不是文件
     * @param unknown $string
     * @param unknown $filename
     * @param string $encoding
     * @param string $type
     */
    public function AddStringAttachment($string, $filename, $encoding = "base64", $type = "application/octet-stream") 
    {
           $this->mail->AddStringAttachment($string, $filename, $encoding, $type); 
    }
	
}
//$mail = new PHPMailer();     //得到一个PHPMailer实例
//$mail->CharSet = "UTF-8";  		//设置采用gb2312中文编码
//$mail->IsSMTP();                 //设置采用SMTP方式发送邮件
//$mail->Host = "mail.ceopen.cn";    //设置邮件服务器的地址
//$mail->Port = 25;                //设置邮件服务器的端口，默认为25
//
//$mail->From     = "swengineer@126.com";  //设置发件人的邮箱地址
//$mail->FromName = "张东宇";         //设置发件人的姓名
//$mail->SMTPAuth = true;            //设置SMTP是否需要密码验证，true表示需要
//
//$mail->Username="swengineer@126.com";
//
//$mail->Password = '!820719z!';
//$mail->Subject = "万选通测试邮件";   //设置邮件的标题
//$mail->AltBody = "text/html";       // optional, comment out and test
//
//
//$mail->Body = "你的邮件的内容";                   
//
//$mail->IsHTML(true);                                        //设置内容是否为html类型
////$mail->WordWrap = 50;                                 //设置每行的字符数
//$mail->AddReplyTo("samzhang@tencent.com","samzhang");     //设置回复的收件人的地址
//
//
// $mail->AddAddress("mailTo@tencent.com","toName");     //设置收件的地址
//if(!$mail->Send()) {                    //发送邮件
//  echo 发送失败:';
// } else {
//  echo "发送成功;
