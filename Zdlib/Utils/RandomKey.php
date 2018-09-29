<?php
/**
 * 
 * 生产随机数,a-z、1-9
 * @author 张东宇
 * @version 1.0
 * @example
 * 	RandomKey::generate_r($length,$isnum);
 *
 */
class Utils_RandomKey {
	public static function generate_r($length = 10,$isnum=false) {
        if($isnum){
            $pattern = '1234567890';//纯数字
        }else{
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ'; //字符池
        }
		$key = '';
		for($i = 0; $i < $length; $i ++) {
			$key .= $pattern {mt_rand ( 0, strlen($pattern)-1)}; //生成php随机数
		}
		return $key;
	}
}