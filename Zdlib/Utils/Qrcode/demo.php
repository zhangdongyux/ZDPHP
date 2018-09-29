<?php
require_once 'autoload.inc';
$g = new Utils_Qrcode_Qrcode('http://www.baidu.com','2.png','H',8,3);
$g->gen();

?>
<html>
	<img alt="二维码" src="http://127.0.0.1/demo/web/module1/2.png">
</html>
