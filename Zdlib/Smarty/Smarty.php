<?php
/**
 * 创建Smarty类，供框架使用
 * 单件模式
 * @author 张东宇
 *
 */
require_once 'webinclude.php';
class Smarty_Smarty {
	
	private static $instance;
	private function __construct(){}
	
	public static function getSmarty() {
		
		if(!isset(self::$instance))
		{
			require_once 'Smarty/Smarty.class.php';
			self::$instance = new Smarty ( );
			//设置Smarty相关目录
			self::$instance->config_dir = SMARTYPATH."/configs"; // 目录变量
			self::$instance->caching = false; //是否使用缓存，项目在调试期间，不建议启用缓存
			self::$instance->template_dir = SMARTYPATH."/templates"; //设置模板目录
			self::$instance->compile_dir = SMARTYPATH."/templates_c"; //设置编译目录
			self::$instance->cache_dir = SMARTYPATH."/smarty_cache"; //缓存文件夹
			self::$instance->left_delimiter="<{";
			self::$instance->right_delimiter="}>";
		}
		return self::$instance;
	}
	
}
?>