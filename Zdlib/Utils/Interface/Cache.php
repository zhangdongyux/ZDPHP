<?php
/**
 * MongoDB缓存类
 * @author zhangdongyu
 * @example 
 * set('m_brand','brand_navigation:'.$subcateId.':'.$manuId, $datas,3600);
 * 1.m_brand:mongodb表名
 * 2.brand_navigation:xx:xx:xx(mongodb中key值，brand-Dao前缀 navigation:方法后缀,xx为参数或自定义)
 * 3.datas:具体缓存内容
 * 4.3600:缓存时间，默认为3600秒
 * get('m_brand','brand_navigation:'.$subcateId.':'.$manuId)
 * 1.m_brand:mongodb表名
 * 2.brand_navigation:xx:xx:xx(mongodb中key值，brand-Dao前缀 navigation:方法后缀,xx为参数或自定义)
 */
abstract class Utils_Interface_Cache
{
	protected  $moduleName = 'wap'; //缓存模块
// 	public static $cacheTable='m_brand';
	
	/**
	 * 设置缓存数据
	 * @param string $table	名称
	 * @param string $key	key
	 * @param string/array $val	数据值
	 * @param number $life	生命周期
	 */
	protected  function set($table,$key,$val,$life=3600)
	{
		mb_convert_variables('GBK', 'UTF-8', $val);
		ZOL_Api::run("Kv.MongoCenter.set" , array(
				'module'         => $this->moduleName,       #业务名
				'tbl'            => $table,         #表名
				'key'            => $key,      #key
				'data'           => $val,           #数据
				'life'           => $life,            #生命期
		));
	}
	
	/**
	 * 获取缓存数据
	 * @param string $table 表名
	 * @param string $key	key
	 */
	protected function get($table,$key)
	{
		$dataArr = ZOL_Api::run("Kv.MongoCenter.get" , array(
				'module'         => $this->moduleName,           #业务名
				'tbl'            => $table,           #表名
				'key'            => $key,           #key
		));
// 		mb_convert_variables('UTF-8', 'GBK', $dataArr);
		return $dataArr;
	}
	
	/**
	 * 返回业务名
	 * @return string
	 */
	public function getModuleName()
	{
	    return $this->moduleName;
	}
	
	/**
	 * 设置业务名
	 * @param string $moduleName
	 */
	public function setModuleName($moduleName='wap')
	{
	    $this->moduleName = $moduleName;
	}
	
}