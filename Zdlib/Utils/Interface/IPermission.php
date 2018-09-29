<?php
/**
 * 权限判定接口
 * @author 张东宇
 * @version 1.0
 * @since 2013-5-7
 */
interface Utils_Interface_IPermission {
	public function validate();		//传入权值、权限码验证方法
	public function validateJson();	//传入权值Json、权限码Json验证方法
	public function redirect($authValue, $code, $url);		//传入权值、权限码验证方法，进行验证后重定向
	public function redirectJson($authValue, $code, $url);	//传入权值Json、权限码Json验证方法，进行验证后重定向
}
