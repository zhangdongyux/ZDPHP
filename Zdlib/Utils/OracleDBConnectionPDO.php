<?
require_once ('autoload.inc');
/**
 * Oracle数据库操作类（单件）
 *
 * 通过php preparedStatement 对数据库进行操作，有效避免sql注入
 *
 * @package Utils
 * @access public
 * @author 张东宇
 * @version 1.0
 */
class Utils_OracleDBConnectionPDO extends Utils_Interface_Adapter {
	private $db;
	private $fetch_style = PDO::FETCH_ASSOC;
	private $debug = false;
	const ADODB_FETCH_DEFAULT = 0;
	const ADODB_FETCH_NUM = 1;
	const ADODB_FETCH_ASSOC = 2;
	const ADODB_FETCH_BOTH = 3;
	public static $instance = null;
	/**
	 * 获取 Utils_OracleDBConnectionPDO 类实例
	 *
	 * @static
	 *
	 * @access public
	 * @return Utils_OracleDBConnectionPDO 返回本类实例
	 */
	public static function getInstance($section) {
		if (is_null ( Utils_OracleDBConnectionPDO::$instance ))
			Utils_OracleDBConnectionPDO::$instance = new Utils_OracleDBConnectionPDO ( $section );
		else {
			$db = Utils_OracleDBConnectionPDO::$instance;
			$status = $db->getDB ()->getAttribute ( PDO::ATTR_SERVER_INFO );
			if (strcasecmp ( $status, 'Oracle server has gone away' )) {
				Utils_OracleDBConnectionPDO::$instance = new Utils_OracleDBConnectionPDO ( $section );
			}
		}
		return Utils_OracleDBConnectionPDO::$instance;
	}
	
	/**
	 * 获取PDO原始对象，可用此对象自行处理相关数据库连接逻辑
	 */
	protected function getDB() {
		return $this->db;
	}
	
	/**
	 * 构造函数
	 *
	 * @access protected 构造函数为保护类型，从父类继承而来
	 * @param mixed $type
	 *        	数据库配置片段,0:配置文件片段，1：数组
	 *        	自己拼接数组形式如下：
	 *        	array('host'=>'xxx','database'=>'xxx','username'=>'xxx','password'=>'xxx','charset'=>'xxx')
	 * @param mixed $section
	 *        	数据库配置片段
	 */
	protected function __construct($section = 'oracle') {
		parent::__construct ( 'config.ini', $section, 1 );
		$tns = "
		(DESCRIPTION =
    	(ADDRESS = (PROTOCOL = TCP)(HOST = $this->host)(PORT = $this->port))
    	(CONNECT_DATA =
      	(SERVER = DEDICATED)
      	(SERVICE_NAME = $this->service_name)
    )
  )
       ";
		
		try {
			$this->db = new PDO ( 'oci:dbname=localhost/XE;charset=AL32UTF8', $this->username, $this->password );
// 			$this->db = new PDO ( 'oci:dbname=' . $tns . ';charset='.$this->charset, $this->username, $this->password );
			$this->db->setAttribute ( PDO::ATTR_CASE, PDO::CASE_NATURAL ); // 设置属性,列名原始大小写，程序员自己控制
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // 出现错误时抛出异常
			$this->db->setAttribute ( PDO::ATTR_EMULATE_PREPARES, true );
		} catch ( PDOException $e ) {
			print $e;
			
			// die ();
		}
	}
	
	/**
	 * 调试sql语句
	 *
	 * @param mixed $sql
	 *        	sql语句，从sql语句配置文件里获得
	 * @param mixed $inputarr
	 *        	参数绑定数组
	 */
	public function debug($sql, $inputarr = array()) {
		if ($this->debug) {
			echo '<hr><font color="red">Debug:<br>';
			foreach ( $inputarr as $key => $value ) {
				if (is_array ( $value ))
					$inputarr [$key] = $value [0];
			}
			$sqlArr = explode ( '?', $sql );
			$fstr = '';
			if (! (count ( $sqlArr ) == 0)) {
				for($i = 0; $i < count ( $sqlArr ); $i ++) {
					$tmp = '';
					$value = $this->db->quote ( $inputarr [$i] );
					if (is_string ( $value ))
						$tmp .= $inputarr [$i];
					else
						$tmp = $inputarr [$i];
					
					$fstr .= $sqlArr [$i] . $tmp;
				}
			} else
				$fstr = $sql;
			
			echo $fstr . '<br></font><hr>';
		}
	}
	
	/**
	 * 是否开启debug功能用于调试sql语句
	 *
	 * @param $flag true-开启，false-关闭，默认false        	
	 */
	public function setDebug($flag = false) {
		$this->debug = $flag;
	}
	
	/**
	 * 数据库查询操作-PDO修改完成
	 *
	 * @param string $sql
	 *        	sql语句，从sql语句配置文件里获得
	 * @param array $inputarr
	 *        	参数绑定数组
	 * @return array 结果集
	 */
	public function query($sql, $inputarr = array()) {
		$rs = $this->db->prepare ( $sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) );
		$tk = 'var';
		$fnum = 1; // for循环中变量赋值
		foreach ( $inputarr as $key => $value ) {
			$tkey = $tk . $key;
			$rs->bindParam ( $fnum, $$tkey );
			$fnum ++;
			$$tkey = $value;
		}
		$this->debug ( $sql, $inputarr );
		$rs->execute ();
		$result = array ();
		while ( @$row = $rs->fetch ( $this->fetch_style ) ) {
			foreach ( $row as $key => $value ) {
				is_resource ( $row [$key] ) && $row [$key] = stream_get_contents ( $value );
			}
			$result [] = $row;
		}
		// $result = $rs->fetchAll ( $this->fetch_style );
		// foreach ( $result as $key => $value ) {
		// foreach ( $value as $vkey => $vvalue ) {
		// is_resource ( $result [$key] [$vkey] ) && $result [$key] [$vkey] =
		// stream_get_contents ($vvalue );
		// }
		// }
		return $result;
		
	}
	/**
	 * 获取数PreparedStatement
	 * 说明：此方法为解决一些特殊情况
	 * 使用此方法，Dao中操作数据库方法请根据PDO规范自行编写
	 * 自行进行结果集解析
	 *
	 *
	 * @param
	 *        	String		SQL语句
	 */
	public function getPreparedStatement($sql) {
		$rs = $this->db->prepare ( $sql );
		return $rs;
	}
	
	/**
	 * 执行存数过程-视情况使用
	 *
	 * @param String $sql
	 *        	sql语句，从sql语句配置文件里获得
	 */
	public function exeProcess($sql) {
		$rs = $this->db->prepare ( $sql );
		return $rs->execute ();
	}
	/**
	 * 数据库执行函数，包括增删改等，无返回值
	 *
	 * @param string $sql
	 *        	sql语句
	 * @param array $inputarr
	 *        	"?"所对应的数组[参数绑定]
	 * @param string $type
	 *        	历史遗留，不用写
	 */
	public function execute($sql, $inputarr = false, $type = null) {
		$rs = $this->db->prepare ( $sql );
		$tk = 'var';
		$fnum = 1; // for循环中变量赋值
		foreach ( $inputarr as $key => $value ) {
			$tkey = $tk . $key;
			if (is_array ( $value )) {
				$rs->bindParam ( $fnum, $$tkey, PDO::PARAM_STR, $value [1] );
				$$tkey = $value [0];
			} else {
				$rs->bindParam ( $fnum, $$tkey );
				$$tkey = $value;
			}
			$fnum ++;
		}
		$this->debug ( $sql, $inputarr );
		return $rs->execute ();
	}
	
	/**
	 * 数据库执行函数，包括增删改等，返回操作影响的行数
	 *
	 * @param string $sql
	 *        	sql语句
	 * @param array $inputarr
	 *        	"?"所对应的数组[参数绑定]
	 * @return int 数字
	 */
	public function execRowNum($sql, $inputarr = array()) {
		$rs = $this->db->prepare ( $sql );
		$rs->execute ( $inputarr );
		$result = $rs->rowCount ();
		if ($result < 0)
			throw new Exception ( 'updateError::' . $sql . ';sqlArray::' . print_r ( $inputarr, true ) );
		$this->debug ( $sql, $inputarr );
		return $result;
	}
	
	/**
	 * 得到数据库uuid
	 */
	function getUUID() {
		$sql = "select UUID() as uuid";
		$result = $this->query ( $sql );
		return $result [0] ['uuid'];
	}
	
	/**
	 * 析构函数，用于关闭数据库连接
	 */
	function __destruct() {
		// $this->db->close();
	}
	
	/**
	 * 定义事务处理方式
	 *
	 * @param boolean $falg
	 *        	自动提交：true，手动提交：false，默认为false
	 */
	public function setAutocommit() {
		$this->db->beginTransaction ();
	}
	/**
	 * 事务提交
	 */
	public function commit() {
		$this->db->commit ();
	}
	/**
	 * 事务回滚
	 */
	public function rollback() {
		$this->db->rollBack ();
	}
	/**
	 * 关闭连接
	 */
	public function close() {
		$this->db->close ();
	}
	
	/**
	 * 取出本次插入ID号
	 *
	 * @return string id号
	 */
	public function insertId() {
		return $this->db->lastInsertId ();
	}
	
	/**
	 * 设置POD返回样式
	 *
	 * @param string $mode2
	 *        	模式字符串
	 */
	public function setFetchMode($mode2 = PDO::FETCH_ASSOC) {
		$this->fetch_style = $mode2;
	}
	
	// /**
	// * 获取指定条数----不要建议使用，建议直接用sql完成
	// *
	// * @param string $sql Sql语句
	// * @param int $numrows 提取的条数
	// * @param int $offset 结束位置
	// * @param array $inputarr 如果有参数绑定，传入绑定数组
	// * @return array 结果集，二维数组
	// */
	// public function selectLimit($sql, $start = -1, $end = -1, $inputarr =
	// false) {
	// return $this->query($sql." limit $start,$end",$inputarr);
	// }
/**
 * 设置debug功能--此类删除，pdo无此功能
 *
 * @param boolean $flag
 *        	ture-开启debug false-关闭debug
 */
	// public function setDebug($flag = false) {
	// $this->db->debug = $flag;
	// }
}
