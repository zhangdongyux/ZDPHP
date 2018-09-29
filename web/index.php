<?php

require_once 'autoload.inc';			//实现autoload

include 'Action/Demo/demoAction.php';

?>
<html>
	<head>
		<title>Demo演示程序</title>
	</head>
	<body>
		<span style="color: blue">***********DB数据获取**********</span>
		<table border="1">
			<tr>
				<th>手机号</th>
				<th>解锁数量</th>
			</tr>
			<tr>
				<td><?php echo $note5Info['tel']?></td>
				<td><?php echo $note5Info['num']?></td>
			</tr>
		</table>
	</body>
</html>
