<?php
/**
 * __autoload
 * 
 * 创建php对象过程中，如果类未找到，那么将自动调用上下文中_autoload函数
 * 
 * @package ZDlib
 * @param  mixed $class_name 类名称，自动判断 
 * @author 张东宇
 * @version 1.2
 * 
 * v1.1修改，使用spl_autoload_register实现自动加载，为兼容PHPExcel
 */
class ZdAutoload {
	// set_include_path(get_include_path() . PATH_SEPARATOR .
	// '../Zdlib/'.PATH_SEPARATOR.'../Zdweb');
	public static function autoLoad($class_name) {
	    
	    $isTmall = explode ( '_', $class_name ); //兼容天猫接口
	    $isTmall = count($isTmall)>1?'0':'1';
		
		// if为Smarty自动载入机制
		$_class = strtolower ( $class_name );
		if (substr ( $_class, 0, 16 ) === 'smarty_internal_' || $_class == 'smarty_security') {
			require_once 'Smarty/sysplugins/' . $_class . '.php';
		} elseif (substr ( $_class, 0, 6 ) === 'logger') 		// if为Logger加载Log4gphp
		{
			require_once 'Logger.php';
		} elseif($isTmall&&$class_name!='WebLibPath'&&$class_name!='SysLibPath'&&$class_name!='PHPExcel'&&$class_name!='Logger') //如果不符合框架自动加载类名规则，转向天猫
		{
    		$name = $class_name;
            if(false !== strpos($name,'\\')){
              $name = strstr($class, '\\', true);
            }
            $TOP_AUTOLOADER_PATH = dirname(__FILE__).'/Tmall';
            $filename = $TOP_AUTOLOADER_PATH."/top/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }
    
            $filename = $TOP_AUTOLOADER_PATH."/top/request/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }
    
            $filename = $TOP_AUTOLOADER_PATH."/top/domain/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }
    
            $filename = $TOP_AUTOLOADER_PATH."/aliyun/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }
    
            $filename = $TOP_AUTOLOADER_PATH."/aliyun/request/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }
    
            $filename = $TOP_AUTOLOADER_PATH."/aliyun/domain/".$name.".php";
            if(is_file($filename)) {
                include $filename;
                return;
            }         
		}else{
			$arrName = explode ( '_', $class_name );
			$dirPath = '';
			foreach ( $arrName as $key => $value ) {
				$key == 0 ? $dirPath .= $value : $dirPath .= '/' . $value;
			}
			require_once ($dirPath . '.php');
		}
	}
}
spl_autoload_register ( array('ZdAutoload','autoLoad' )); 