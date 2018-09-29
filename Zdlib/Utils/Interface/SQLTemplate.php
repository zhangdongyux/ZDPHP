<?
require_once ('autoload.inc');
/**
 * SQL模板
 * 
 * sql模板抽象类，其中定义了查询数据库的相关配置，如sql语句，解析sql文件名等
 * 作者：张东宇
 * 
 * @package Utils-Interface
 * @access public
 * @abstract 抽象类
 * @author 张东宇
 * @version 1.0
 */
abstract class Utils_Interface_SQLTemplate extends Utils_Interface_Cache
{
	protected $sqls; //sql配置文件中提取的sql语句数组
	protected $filepath; //子类路径（名称），必须为子类的__CLASS__值
	protected $filename; //sql文件名
	protected $dao; //数据访问对象
	protected $isOracle; //否是为Oracle
	

	/**
	 * 构造函数
	 * 
	 * @param mixed $filepath 子类路径（即子类__CLASS__）
	 * @param mixed $section 数据库配置文件片段名称
	 * @param mixed $filename sql文件名
	 * @param mixed $isOracle 操作类型是否为Oracle数据库，默认为Mysql(0),Oracle为1
	 */
	public function __construct($filepath, $section, $filename, $isOracle = 0) {
		//			echo $filename;exit;	
		$this->filepath = $filepath;
		$this->filename = $filename;
		$this->isOracle = $isOracle;
		//		if ('en_us' == $_COOKIE['ZDLANG']) {
		//			$this->filename=$filename;
		//			if(!file_exists(WebLibPath::path().$this->loadPath()))
		//			{
		$this->filename = $filename;
		//			}
		//		}
		if (! file_exists ( WebLibPath::path () . $this->loadPath () ) && ! file_exists ( SysLibPath::path () . $this->loadPath () ))
			throw new Exception_CEException ( "File " . $filename . ' not found !!' );
		if ($this->isOracle)
			$this->dao = Utils_OracleDBConnectionPDO::getInstance ( $section );
		else
			$this->dao = Utils_MysqlDBConnectionPDO::getInstance ( $section,$this->filepath );
		//$this->loadPath($filename);
	}
	
	/**
	 * 返回指定SQL语句
	 * 
	 * @param  mixed $name  sql键
	 * @return String		返回具体的sql语句
	 */
	public function getSQL($name) {
		$sqls = parse_ini_file ( $this->loadPath () );
		return $sqls [$name];
	}
	/**
	 * 通过子类__CLASS__值得到sql文件的具体路径，sql文件名在子类定义，默认为sql_zh_cn.inc
	 *
	 * @return String sql文件路径
	 */
	private function loadPath() {
		$arrName = explode ( '_', $this->filepath );
		unset ( $arrName [count ( $arrName ) - 1] );
		$dirPath = "";
		foreach ( $arrName as $key => $value ) {
			$key == 0 ? $dirPath .= $value : $dirPath .= "/" . $value;
		}
		return $dirPath . '/' . $this->filename;
	}
}