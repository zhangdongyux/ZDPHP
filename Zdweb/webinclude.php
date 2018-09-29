<?php 
/**
 * 网站相关资源路径配置文件
 * 
 * 使用方法：
 * 		在此文件中定义全局变量，全局变量名称大写
 * 		全局变量一般为资源路径，不包含其他
 * 
 * 作者：张东宇
 * 版本：V1.0
 *
 */

//定义品牌专区路径信息
define('JSPATH_BRAND','http://icon.xx.com/m/js/brand');
define('CSSPATH_BRAND','http://icon.xx.com/m/css/brand');
define('IMAGEPATH_BRAND','http://icon.xx.com/m/images/brand');

//定义Smarty跟路径
define('SMARTYPATH','/usr/data/www/demo/Zdweb/SmartyWeb');

//定义前台资源全局变量
define('WEBHOST','http://www.XXX.com');
define('CSSPATH',WEBHOST.'/resources/css');
define('JSPATH',WEBHOST.'/resources/javascript');
define('IMAGEPATH',WEBHOST.'/resources/images');

session_start();

