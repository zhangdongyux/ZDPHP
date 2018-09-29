<?php 
/**
 * 功能：生成验证码
 * 作者：张东宇(网络改造)
 * 说明：
 * 		基本和网上一样，没有进行二次类封装，不封装看代码时候好理解，并容易修改
 * 		可以实现中文验证码，修改改造写文字部分，以及显示宽度的调整
 * 版本：V1.0
 * 
 * Demo:
 * 
 * 拷贝此文件到web层任意目录
 * <img src="yanzhengma.php">
 * 
 * 验证页面:(建议Ajax验证)
 * session_start();
 * echo  strtolower($_SESSION['VCODE']);
 * 
 */
//文件头... 
header("Content-type: image/png"); 
//创建真彩色图像 
$im = @imagecreatetruecolor(75, 25) or die("建立图像失败"); 
//获取背景颜色 (白色)，函数返回一个int
$background_color = imagecolorallocate($im, 255, 255, 255); 
//从指定坐标开始填充背景颜色(这个东西类似油桶) 
imagefill($im,0,0,$background_color); 
//获取边框颜色 
$border_color = imagecolorallocate($im,200,200,200); 
//重指定坐标开始画矩形，边框颜色200,200,200
imagerectangle($im,0,0,74,24,$border_color); 
//逐行炫耀背景，全屏用1或0 
for($i=2;$i<23;$i++){ //纵坐标
//获取随机淡色 
$line_color = imagecolorallocate($im,rand(200,255),rand(200,255),rand(200,255)); 
//从指定坐标开始画线 
imageline($im,2,$i,72,$i,$line_color); 
} 
//设置字体大小 
$font_size=15; 
//设置印上去的文字 
$Str[0] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
$Str[1] = "abcdefghijklmnopqrstuvwxyz"; 
$Str[2] = "01234567890123456789012345"; 
//获取第1个随机文字 
$imstr[0]["s"] = $Str[rand(0,2)][rand(0,25)]; 
$imstr[0]["x"] = rand(8,15); 
$imstr[0]["y"] = rand(1,4); 
//获取第2个随机文字 
$imstr[1]["s"] = $Str[rand(0,2)][rand(0,25)]; 
 //可以直接加上font_size设置字间距，后面随即部分省略
$imstr[1]["x"] = $imstr[0]["x"]+$font_size-1+rand(0,1);
$imstr[1]["y"] = rand(1,3); 
//获取第3个随机文字 
$imstr[2]["s"] = $Str[rand(0,2)][rand(0,25)]; 
$imstr[2]["x"] = $imstr[1]["x"]+$font_size-1+rand(0,1); 
$imstr[2]["y"] = rand(1,4); 
//获取第4个随机文字 
$imstr[3]["s"] = $Str[rand(0,2)][rand(0,25)]; 
$imstr[3]["x"] = $imstr[2]["x"]+$font_size-1+rand(0,1); 
$imstr[3]["y"] = rand(1,3); 
//写入随机字串 
//$pos=0;
for($i=0;$i<4;$i++){ 
//获取随机较深颜色 
$text_color = imagecolorallocate($im,rand(50,180),rand(50,180),rand(50,180)); 
//画文字

/***********汉字-需要相应字库文件*.ttf******
//$pos = $i*15+$i*7;
//ImageTTFText($im, 15, 0, $pos, 18, $text_color,'SIMYOU.TTF',$imstr[$i]["s"]);
**********************/
imagechar($im,$font_size,$imstr[$i]["x"],$imstr[$i]["y"],$imstr[$i]["s"],$text_color);
} 
//显示图片 
imagepng($im); 
//销毁图片--释放图片相关内存
imagedestroy($im); 
session_start();
$_SESSION['VCODE']= $imstr[0]["s"].$imstr[1]["s"].$imstr[2]["s"].$imstr[3]["s"];
?>