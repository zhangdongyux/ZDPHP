<?php
/**
 * Demo程序,......
 * 
 * @access public
 * @author 张东宇
 * @version V1.0
 *
 */
class Dao_Demo_DemoDao extends Utils_Interface_SQLTemplate  {
	
	/**
	 * 1.覆盖超类的构造函数，构造函数的参数为存放sql语句文件的文件名
	 * 2.调用超类的构造函数，有两个参数：1.__CLASS__ 2,$sqlFile
	 *
	 */
	public function __construct($section="dbserver_active",$sqlFile='sql_zh_cn.inc')
	{
		parent::__construct(__CLASS__,$section,$sqlFile);
		$this->dao->setDbCharset('gbk');
		$this->dao->setHtmlCharset('utf8');
	}
	
	/**
	 * 根据手机号获取用户信息
	 * @param string $tel 手机号
	 * @return 用户信息
	 */
	public function getNote5ByTel($tel='13811115169')
	{
		$sqlArray = array($tel);
		$rs = $this->dao->query($this->getSQL('demo.getNote5ByTel'),$sqlArray);
		return $rs[0];
	}
	
}