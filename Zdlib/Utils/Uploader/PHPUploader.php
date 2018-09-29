<?php
/**
 * PHP上传类
 * 
 * 提供图片上传功能，支持多文件上传
 * 
 * 使用方法：
 * 		$tt = new FileUpload();
 *		echo $tt->upload();
 * 		
 * 		upload()方法返回代码如下：
 * 
 * 		0--没有错误发生，文件上传成功
 * 		1--上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值
 * 		2--上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
 * 		3--文件只有部分被上传
 * 		4--没有文件被上传
 * 		5--上传文件类型错误
 * 		6--上传文件过大
 * 		7--上传出错(服务器拷贝文件时发生),由upload()方法生成
 * 
 * @access public
 * @author 张东宇
 * @version 1.0
 */

class Utils_Uploader_PHPUploader
{
	private $directory='C:'; //上传主目录，根据实际项目调整
	private $maxSize=1024;	//文件最大字节，根据实际项目调整
	private $extType=array('jpg','txt','pdf'); //允许扩展，根据实际项目调整
	private $saveURL='http://';//保存的URL路径，为KingEditor添加
	private $fileData;	//表单名称，通过构造函数自动赋值
	private $dbUrl = "";//上传后返回图片路径，用于Action入库使用
	public function __construct($directory='',$extType='',$maxSize='',$saveURL='')
	{
		if($directory!='')
			$this->directory = $directory;
		if($extType!='')
			$this->extType = $extType;
		if($maxSize!='')
			$this->maxSize = $maxSize;
		if($saveURL!='')
			$this->saveURL=$saveURL;
				foreach ($_FILES as $key=>$value)
					$this->fileData[] =$key;
// 		print_r($this->fileData);
	}
	
	/**
	 * 创建目录
	 *
	 * @param string $path 完整文件路径
	 * @return 函数指针 //测试可不返回
	 */
	private function createFolder($path)
	{
		if(!file_exists($path))
		{
			$this->createFolder(dirname($path));
			@mkdir($path,0777);
		}
	  return $this->createFolder; //有无均可
	}
	
	/**
	 * 验证文件信息是否正确
	 * 
	 * 验证代码如下：
	 * 		0--4参考getFileInfo()方法
	 * 		5--上传文件类型错误
	 * 		6--上传文件过大
	 * 		7--上传出错(服务器拷贝文件时发生),有upload()方法生成
	 * 
	 * @return 验证结构，json格式 {"error:0"}表示验证成功
	 */
	private function validate()
	{
		$message='0';
			foreach($this->fileData as $inputName)
			{
				$ext = explode('.',$this->getFileInfo($inputName,'name'));
				$size = $this->getFileInfo($inputName,'size');
				if(intval($this->getFileInfo($inputName,'error'))!=0)
				{
					$message = $this->getFileInfo($inputName,'error'); break;		//上传文件失败
				}
				elseif(in_array(strtolower(array_pop($ext)),$this->extType)===false)
				{
					$message = '5';	break;	//上传文件类型错误
				}
				elseif (intval($size)>$this->maxSize*1024)
				{
					$message = '6';	break;	//上传文件过大
				}
			}
//		}
		return $message;
	}
	
	/**
	 * 获取文件详细内容
	 *
	 * 包括：
	 * 		扩展类型、文件名、临时文件名、错误代码、文件大小
	 * 		其中：错误类型码如下：
	 * 				0--没有错误发生，文件上传成功。
	 * 				1--上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。
	 * 				2--上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。
	 * 				3--文件只有部分被上传。
	 * 				4--没有文件被上传。
	 * @param string $fileType 文件信息key 包括：name/type/size/tmp_name/error
	 * @return string 获取结果
	 */
	private function getFileInfo($inputName,$fileType)
	{
		return $_FILES[$inputName][$fileType];
	}
	
	/**
	 * 上传处理
	 * 
	 * 根据年份/月份建立目录，进行上传，文件名采用 年月日_md5(time(),0-1000随机数)
	 *
	 *@editor_type 为兼容 KingEditor编辑器而添加
	 */
	function upload($editor_type='')
	{
		$message = $this->validate();
		if($message!='0')
		{
			if($editor_type=='king')
				$this->showKingError($message);
			return $message;
		}

		$dateDir=date('Y').'/'.date('m');
		$directory = $this->directory.'/'.$dateDir;		//上传目录
		if(@is_dir($directory)===false)									//检查目录是否存在
			$this -> createfolder($directory);							//不存在创建目录
	 	if (@is_writable($directory) === false) {
         	chmod($directory,0777);
     	}
		foreach ($this->fileData as $inputName)
		{
			$fileType = array_pop(explode('.',$this->getFileInfo($inputName,'name')));
			$fileNewName = date('Ymd').'_'.md5(time().rand(0,1000)).'.'.$fileType; //重命名
			$tmp_name = $this->getFileInfo($inputName,'tmp_name');
	     	if(@move_uploaded_file($tmp_name,$directory.'/'.$fileNewName)===false)
	     	{
	     		$message = '7';break; 
	     	}
	     	
	     	$this->dbUrl=array($dateDir.'/'.$fileNewName);//获取商圈图片路径为Action使用，可入数据库 2013年10月31日添加
     	
		}
		if($editor_type=='king')
			$this->showKingError($message,$dateDir.'/'.$fileNewName);//KingEditor返回单张图片RUL
    	 return $message;
	}
	
	/**
	 * 返回存储URL，以便入库使用
	 * @param int	是否多文件上传，0-单文件 1-多文件 默认0
	 * @param string $baseUrl 数据URL前缀，根据项目填写
	 * @return 为支持多文件上传，返回数组形式array
	 */
	public function getPicDBURL($type=0,$baseUrl='')
	{
		if($type!=0&&$type!=1)
			return '11';
		elseif($type==0)
			return $this->dbUrl[0];
		foreach ($this->dbUrl as $key=>$pic)
		{
			if($baseUrl!='')
			{
				$this->dbUrl[$key]=$baseUrl.'/'.$this->dbUrl[$key];
			}
		}
		return $this->dbUrl;
	}
	
	
	function showKingError($message,$fileNewName='')
	{
		$error='未知错误111';
		if($message!=0)
		{
			switch($message){
				case '1':
					$error = '超过php.ini允许的大小。';
					break;
				case '2':
					$error = '超过表单允许的大小。';
					break;
				case '3':
					$error = '图片只有部分被上传。';
					break;
				case '4':
					$error = '请选择图片。';
					break;
				case '5':
					$error = '请选择正确的图片格式。';
					break;
				case '6':
					$error = '上传文件过大。';
					break;
				case '7':
					$error = '写文件到硬盘出错。';
					break;
				case '8':
					$error = 'File upload stopped by extension。';
					break;
				case '999':
				default:
					$error = '未知错误。';
			}
			$this->alert($error);
			exit;
		}
		header('Content-type: text/html; charset=UTF-8');
		echo json_encode(array('error' => 0, 'url' => $this->saveURL.'/'.$fileNewName,'dburl'=>$fileNewName));
		exit;
	}
	
	/**
	 * 为KingEditor编辑器而添加
	 * @param  string $msg 发送到编辑器消息提示
	 */
	function alert($msg) {
		header('Content-type: text/html; charset=UTF-8');
		// 	$json = new Services_JSON();
		// 	echo $json->encode(array('error' => 1, 'message' => $msg));
		echo json_encode(array('error' => 1, 'message' => $msg));
		exit;
	}
}


//为KingEditor编辑器添加
/**
 * KingEditor编辑器回调显示，返回指定JSON格式，用于编辑器回显用户提示信息
 * @param unknown_type $message
 */


// if ($_SERVER['REQUEST_METHOD']=='POST')
// {
// 	$tt = new FileUpload();
// 	echo $tt->upload();
// }
// <html>
// 	<body>
// 		<form action="" method="post" enctype="multipart/form-data">
// 			<input type="file" name='filename'/>
// 			<input type="file" name='filename1'/>
// 			<input type="submit" />
// 		</form>
// 	</body>
// </html>