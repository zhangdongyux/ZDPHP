<?
/**
 * Mysql数据库操作类（单件）
 * 
 * 通过php preparedStatement 对数据库进行操作，有效避免sql注入
 * 
 * @package Utils
 * @access public
 * @author 张东宇
 * @version 1.0
 */
// require_once 'Adapter.php';
class Utils_MysqlDBConnectionPDO extends Utils_Interface_Adapter {
	private $dbCharset = 'gbk';
	private $htmlCharset = 'utf8';
	private $db;
	private $fetch_style = PDO::FETCH_ASSOC;
	private $debug = false;
	private $comment = ''; //注释
	const ADODB_FETCH_DEFAULT = 0;
	const ADODB_FETCH_NUM = 1;
	const ADODB_FETCH_ASSOC = 2;
	const ADODB_FETCH_BOTH = 3;
	public static $instance = null;
	/**
	 * 获取 Utils_MysqlDBConnectionPDO 类实例
	 *
	 * @static
	 * @access public
	 * @return Utils_MysqlDBConnection 返回本类实例 
	 */
	public static function getInstance($section,$comment='') {
		if (is_null ( Utils_MysqlDBConnectionPDO::$instance ))
			Utils_MysqlDBConnectionPDO::$instance = new Utils_MysqlDBConnectionPDO ( $section,$comment );
		else {
			$db = Utils_MysqlDBConnectionPDO::$instance;
			$status = $db->getDB ()->getAttribute ( PDO::ATTR_SERVER_INFO );
			if (strcasecmp ( $status, 'MySQL server has gone away' )) {
				Utils_MysqlDBConnectionPDO::$instance = new Utils_MysqlDBConnectionPDO ( $section ,$comment);
			}
		}
		return Utils_MysqlDBConnectionPDO::$instance;
	}
	
	/**
	 * 
	 * 获取PDO原始对象，可用此对象自行处理相关数据库连接逻辑
	 */
	protected function getDB() {
		return $this->db;
	}
	
	/**
	 * 构造函数
	 * 
	 * @access protected 构造函数为保护类型，从父类继承而来
	 * @param mixed $type 数据库配置片段,0:配置文件片段，1：数组
	 * 自己拼接数组形式如下：
	 * array('host'=>'xxx','database'=>'xxx','username'=>'xxx','password'=>'xxx','charset'=>'xxx')
	 * @param mixed $section 数据库配置片段
	 */
	protected function __construct($section = 'mysql',$comment='') {
	    $this->comment = $comment;
		parent::__construct ( 'config.ini', $section );
		$dsn = "mysql:host=$this->host;dbname=$this->database;port=$this->port";
		try {
			$this->db = new PDO ( $dsn, $this->username, $this->password );
			$this->db->setAttribute ( PDO::ATTR_CASE, PDO::CASE_NATURAL ); //设置属性,列名原始大小写，程序员自己控制
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //出现错误时抛出异常
			$this->db->query ( "set names '$this->charset'" );
		} catch ( PDOException $e ) {
			header('HTTP/1.1 500 Internal Server Error'); 
			print "服务器繁忙，请您稍后再试！";
// 			echo '<pre>';print_r($e);
			die ();
		}
	}
	
	/**
	 * 调试sql语句
	 * 
	 * @param mixed $sql sql语句，从sql语句配置文件里获得
	 * @param mixed $inputarr 参数绑定数组
	 */
	public function debug($sql, $inputarr = array()) {
		if ($this->debug) {
			echo '<hr><font color="red">Debug:<br>';
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
	 * @param string $sql sql语句，从sql语句配置文件里获得
	 * @param array $inputarr 参数绑定数组
	 * @return array  结果集
	 */
	public function query($sql, $inputarr = array()) {
		$rs = $this->db->prepare ( $sql.'/*--Location--M:'.$this->comment.'*/' );
		$tk = 'var';
		$fnum = 1; //for循环中变量赋值
		foreach ( $inputarr as $key => $value ) {
			$tkey = $tk . $key;
			$rs->bindParam ( $fnum, $$tkey );
			$fnum ++;
			$$tkey = $value;
		}
		$rs->execute ();
		$this->debug ( $sql, $inputarr );
		$result = $rs->fetchAll ( $this->fetch_style );
		if($this->dbCharset&&$this->htmlCharset)
			mb_convert_variables($this->htmlCharset,$this->dbCharset , $result);
		return $result;
	}
	/**
	 * 获取数PreparedStatement
	 * 说明：此方法为解决一些特殊情况
	 * 使用此方法，Dao中操作数据库方法请根据PDO规范自行编写
	 * 自行进行结果集解析
	 * 
	 * 
	 * @param String		SQL语句
	 */
	public function getPreparedStatement($sql) {
		$rs = $this->db->prepare ( $sql );
		return $rs;
	}
	
	/**
	 * 执行存数过程-视情况使用
	 *
	 * @param String $sql sql语句，从sql语句配置文件里获得
	 */
	public function exeProcess($sql) {
		$rs = $this->db->prepare ( $sql );
		return $rs->execute ();
	}
	/**
	 * 数据库执行函数，包括增删改等，无返回值
	 *
	 * @param string $sql sql语句
	 * @param array $inputarr "?"所对应的数组[参数绑定]
	 * @param string $type 历史遗留，不用写
	 */
	public function execute($sql, $inputarr = array(), $type = null) {
		if($this->dbCharset&&$this->htmlCharset)
			mb_convert_variables($this->dbCharset,$this->htmlCharset , $inputarr);
		$rs = $this->db->prepare ( $sql.'/*--Location--M:'.$this->comment.'*/' );
		$this->debug ( $sql, $inputarr );
		return $rs->execute ( $inputarr );
	}
	/**
	 * 数据库执行函数，包括增删改等，返回操作影响的行数
	 * @param string $sql sql语句
	 * @param array $inputarr "?"所对应的数组[参数绑定]
	 * @return int 数字
	 */
	public function execRowNum($sql, $inputarr = array()) {
		if($this->dbCharset&&$this->htmlCharset)
			mb_convert_variables($this->dbCharset,$this->htmlCharset , $inputarr);
		$rs = $this->db->prepare ( $sql.'/*--Location--M:'.$this->comment.'*/' );
		$rs->execute ( $inputarr );
		$result = $rs->rowCount ();
		if ($result < 0)
			throw new Exception ( 'updateError::' . $sql . ';sqlArray::' . print_r ( $inputarr, true ) );
		$this->debug ( $sql, $inputarr );
		return $result;
	}
	
	/**
	 * 
	 * 得到数据库uuid
	 */
	function getUUID() {
		$sql = "select UUID() as uuid";
		$result = $this->query ( $sql );
		return $result [0] ['uuid'];
	}
	
	/**
	 * 析构函数，用于关闭数据库连接
	 *
	 */
	function __destruct() {
		//$this->db->close();
	}
	
	/**
	 * 定义事务处理方式
	 *
	 * @param boolean $falg 自动提交：true，手动提交：false，默认为false
	 */
	public function setAutocommit() {
		$this->db->beginTransaction ();
	}
	/**
	 * 事务提交
	 *
	 */
	public function commit() {
		$this->db->commit ();
	}
	/**
	 * 事务回滚
	 *
	 */
	public function rollback() {
		$this->db->rollBack ();
	}
	/**
	 * 关闭连接
	 *
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
	 * @param string $mode2 模式字符串 
	 */
	public function setFetchMode($mode2 = PDO::FETCH_ASSOC) {
		$this->fetch_style = $mode2;
	}
	
	/**
	 * 设置数据库编码
	 * @param string $charset
	 */
	public function setDbCharset($charset = false)
	{
		$this->dbCharset = $charset;
	}
	
	/**
	 * 设置页面显示编码
	 * @param string $charset
	 */
	public function setHtmlCharset($charset = false)
	{
		$this->htmlCharset = $charset;
	}

}