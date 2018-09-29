<?php
/**
 * 设置Mongodb缓存公共类
 * 说明：
 * 1.在Dao类中的方法中统一使用此类设置Mongodb缓存
 * $cacheTable为mongodb存储业务的的表名
 * key格式应为:$prefixKey.'_dao模块名_'.'_方法名_:'.'方法参数1:'.'参数2'......
 * 
 * 示例：
 * Dao类为：Dao_Brand_BrandDao
 * 方法为：getNumsForBrand($subcateId=57,$manuId=98)
 * 
 * key最终应为：wap_brand_numsForBrand:57:98
 * 
 * @author zhangdongyu
 *
 */
class Utils_MongodbCache
{
	private $module = 'wap'; //缓存模块
	private $cacheTable = '';//mogodb表名
	private $prefixKey = ''; //key前缀
	
	/**
	 * 构造方法
	 * @param unknown $module  模块名 默认wap
	 * @param unknown $cacheTable  //mongodb表名
	 * @param unknown $key //mongodbKey前缀
	 */
	public function __construct($cacheTable,$prefixKey,$module='wap')
	{
	    $this->cacheTable = $cacheTable;
	    $this->prefixKey = $prefixKey;
	    $this->module = $module;
	}
	
	/**
	 * 设置缓存数据
	 * @param string $key	key  键
	 * @param string/array $val	数据值
	 * @param number $life	生命周期 默认一个小时
	 */
	public  function set($key,$val,$life=3600)
	{
		mb_convert_variables('GBK', 'UTF-8', $val);
		ZOL_Api::run("Kv.MongoCenter.set" , array(
				'module'         => $this->module,       #业务名
				'tbl'            => $this->cacheTable,         #表名
				'key'            => $this->prefixKey.'_'.$key,      #key
				'data'           => $val,           #数据
				'life'           => $life,            #生命期
		));
	}
	
	/**
	 * 获取缓存数据
	 * @param string $key	key 键
	 * @return mongodb中数据
	 */
	public function get($key)
	{
		$dataArr = ZOL_Api::run("Kv.MongoCenter.get" , array(
				'module'         => $this->module,           #业务名
				'tbl'            => $this->cacheTable,           #表名
				'key'            => $this->prefixKey.'_'.$key,           #key
		));
// 		mb_convert_variables('UTF-8', 'GBK', $dataArr);
		return $dataArr;
	}
	
}