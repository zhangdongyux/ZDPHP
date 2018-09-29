<?php
/**
 * Request URL:http://xxxxxxx/Demo.php?request=getuser&address=beiing&sign=cc8bd3bd1be78b30f930babf74cdcd90
 */
require_once 'Interface.php';
class Rest_Demo extends Rest_Interface
{
	public function Rest_Demo()
	{
		parent::Rest_Interface();
		$this->processApi();
	}
	
	public function getUser()
	{
// 		echo "getUser <br>";
        $address = isset($_GET['address'])?$_GET['address']:'';
		$user = array('username'=>'张三','password'=>'123123','address'=>$address);
// 		print_r(json_encode($user));
// 		exit;
		$this->response(json_encode($user),200);
// 		print_r($this->getRequest());
	}
	
	public function upload()
	{
		echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	  	echo "Type: " . $_FILES["file"]["type"] . "<br />";
	  	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	  	echo "Stored in: " . $_FILES["file"]["tmp_name"];
	}
}
new Rest_Demo();